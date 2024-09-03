<?php

namespace App\Controller;

use App\Entity\Hauling\Hauling;
use App\Form\Hauling\HaulingType;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    )
    {
    }

    #[Route('/', name: 'APP_HOMEPAGE')]
    public function index(Request $request): Response
    {
        $hauling = new Hauling();
        $form    = $this->createForm(HaulingType::class, $hauling);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($hauling);
        }

        $paths = [];
        foreach ($this->locationRepository->getRootNodes() as $rootNode) {
            $locations = $this->locationRepository->getLeafs($rootNode);
            foreach ($locations as $location) {
                $paths[] = $this->locationRepository->getPath($location);
            }
        }
        return $this->render('homepage/index.html.twig', [
            'form'  => $form->createView(),
            'paths' => $paths,
        ]);
    }
}
