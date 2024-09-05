<?php

namespace App\Controller;

use App\Entity\Hauling\Hauling;
use App\Entity\User;
use App\Form\Hauling\HaulingType;
use App\Repository\Hauling\HaulingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    public function __construct(
        private readonly HaulingRepository      $haulingRepository,
        private readonly EntityManagerInterface $em,
        private readonly RequestStack           $requestStack,
    )
    {
    }

    #[Route('/', name: 'APP_HOMEPAGE')]
    public function index(Request $request): Response
    {
        $hauling = $this->getHauling();
        $form = $this->createForm(HaulingType::class, $hauling);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($hauling);
            $this->em->flush();
            return $this->redirectToRoute('APP_HOMEPAGE');
        }
        return $this->render('homepage/index.html.twig', [
            'hauling' => $hauling,
        ]);
    }

    private function getHauling(): Hauling
    {
        if ($hauling = $this->getCurrentUserHauling()) {
            return $hauling;
        }
        if ($user = $this->getUser()) {
            $hauling = (new Hauling())->setUser($user);
            $this->em->persist($hauling);
            $this->em->flush();
            return $hauling;
        }
        $session = $this->requestStack->getSession();
        if (!$anonymousId = $session->get(User::ANONYMOUS_USER_ID_SESSION_KEY)) {
            $anonymousId = uniqid('anonymous_id', true);
            $session->set(User::ANONYMOUS_USER_ID_SESSION_KEY, $anonymousId);
        }
        $hauling = (new Hauling())->setAnonymousUser($anonymousId);
        $this->em->persist($hauling);
        $this->em->flush();
        return $hauling;
    }

    private function getCurrentUserHauling(): ?Hauling
    {
        if ($user = $this->getUser()) {
            return $this->haulingRepository->findByUser($user);
        }
        $session = $this->requestStack->getSession();
        if (!$anonymousId = $session->get(User::ANONYMOUS_USER_ID_SESSION_KEY)) {
            $anonymousId = uniqid('anonymous_id', true);
            $session->set(User::ANONYMOUS_USER_ID_SESSION_KEY, $anonymousId);
        }
        return $this->haulingRepository->findByAnonymousUser($anonymousId);
    }
}
