<?php

namespace App\Service\Hauling;

use App\Entity\Hauling\Cargo;
use App\Entity\Hauling\Hauling;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JBZoo\MermaidPHP\Graph;
use JBZoo\MermaidPHP\Link;
use JBZoo\MermaidPHP\Node;
use JBZoo\MermaidPHP\Render;
use Symfony\Component\String\Slugger\AsciiSlugger;

class HaulingToMermaid
{
    private Graph $graph;
    private array $paths = [];
    private ArrayCollection $linkAdded;

    public function __construct(
        private readonly LocationRepository $locationRepository,
    )
    {
        $this->linkAdded = new ArrayCollection();
    }

    public function convert(Hauling $hauling): Graph
    {
        $this->graph = new Graph();

        $this->initLocationsNodes($hauling);

        foreach ($hauling->getRoutes() as $route) {
            $fromLocation = $route->getFromLocation();
            $fromNode     = $this->getLocationNode($fromLocation);

            $toLocation = $route->getToLocation();
            $toNode     = $this->getLocationNode($toLocation);

            $linkId = sprintf('%s_%s', $fromNode->getId(), $toNode->getId());

            if ($this->linkAdded->get($linkId)) {
                $this->linkAdded->remove($linkId);
            }

            $this->linkAdded->set($linkId, new Link(
                $fromNode,
                $toNode,
                implode("\n", array_map(static function (Cargo $cargo) {
                    return sprintf("%s x%d", $cargo->getCommodity()->getTitle(), $cargo->getQuantity());
                }, $route->getCargos()->toArray())),
            ));
        }

        foreach ($this->linkAdded as $item) {
            $this->graph
                ->addLink($item)
            ;
        }

        return $this->graph;
    }

    private function initLocationsNodes(Hauling $hauling)
    {
        $locations = [];
        foreach ($hauling->getRoutes() as $route) {
            $locations[$route->getFromLocation()->getId()] = $route->getFromLocation();
            $locations[$route->getToLocation()->getId()]   = $route->getToLocation();
        }
        $locations = array_values($locations);

        foreach ($locations as $location) {
            $previousNode = null;
            foreach ($this->getLocationPath($location) as $item) {
                $node = $this->getLocationNode($item);
                if ($previousNode) {
                    $linkId = sprintf('%s_%s', $previousNode->getId(), $node->getId());

                    if ($this->linkAdded->get($linkId)) {
                        $previousNode = $node;
                        continue;
                    }

                    $this->linkAdded->set($linkId,new Link(
                        $previousNode,
                        $node,
                    ));
                }
                $previousNode = $node;
            }
        }
    }

    private function getLocationPath(Location $location)
    {
        if ($path = $this->paths[$location->getId()] ?? null) {
            return $path;
        }
        $path                            = $this->locationRepository->getPath($location);
        $this->paths[$location->getId()] = $path;
        return $path;
    }

    private function getLocationNode(Location $location): Node
    {
        $nodeIdentifier = "location" . $location->getId();
        if (!$node = $this->graph->getNodes()[$nodeIdentifier] ?? null) {
            $node = new Node(
                $nodeIdentifier,
                substr($location->getTitle(), 0, 25),
                Node::SQUARE,
            );
            $this->graph->addNode($node);
        }
        return $node;
    }
}