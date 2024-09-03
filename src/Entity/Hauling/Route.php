<?php

namespace App\Entity\Hauling;

use App\Entity\Location;
use App\Repository\Hauling\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $fromLocation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $toLocation = null;

    /**
     * @var Collection<int, Cargo>
     */
    #[ORM\OneToMany(targetEntity: Cargo::class, mappedBy: 'route', orphanRemoval: true)]
    private Collection $cargos;

    #[ORM\ManyToOne(inversedBy: 'routes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hauling $hauling = null;

    public function __construct()
    {
        $this->cargos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromLocation(): ?Location
    {
        return $this->fromLocation;
    }

    public function setFromLocation(?Location $fromLocation): static
    {
        $this->fromLocation = $fromLocation;

        return $this;
    }

    public function getToLocation(): ?Location
    {
        return $this->toLocation;
    }

    public function setToLocation(?Location $toLocation): static
    {
        $this->toLocation = $toLocation;

        return $this;
    }

    /**
     * @return Collection<int, Cargo>
     */
    public function getCargos(): Collection
    {
        return $this->cargos;
    }

    public function addCargo(Cargo $cargo): static
    {
        if (!$this->cargos->contains($cargo)) {
            $this->cargos->add($cargo);
            $cargo->setRoute($this);
        }

        return $this;
    }

    public function removeCargo(Cargo $cargo): static
    {
        if ($this->cargos->removeElement($cargo)) {
            // set the owning side to null (unless already changed)
            if ($cargo->getRoute() === $this) {
                $cargo->setRoute(null);
            }
        }

        return $this;
    }

    public function getHauling(): ?Hauling
    {
        return $this->hauling;
    }

    public function setHauling(?Hauling $hauling): static
    {
        $this->hauling = $hauling;

        return $this;
    }
}
