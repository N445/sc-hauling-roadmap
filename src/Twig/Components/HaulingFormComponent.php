<?php

namespace App\Twig\Components;

use App\Entity\Hauling\Hauling;
use App\Form\Hauling\HaulingType;
use App\Service\Hauling\HaulingToMermaid;
use Doctrine\ORM\EntityManagerInterface;
use JBZoo\MermaidPHP\Graph;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class HaulingFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        private readonly HaulingToMermaid $haulingToMermaid,
    )
    {
    }

    #[LiveProp]
    public ?Hauling $initialFormData = null;

    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(HaulingType::class, $this->initialFormData);
    }

    public function getMermaidSchema(): Graph
    {
        return $this->haulingToMermaid->convert($this->initialFormData);
    }

    #[LiveAction]
    public function save(EntityManagerInterface $em): RedirectResponse
    {
        // Submit the form! If validation fails, an exception is thrown
        // and the component is automatically re-rendered with the errors
        $this->submitForm();

        /** @var Hauling $hauling */
//        $hauling = $this->getForm()->getData();
        $hauling = $this->initialFormData;
        dump($hauling);
        dump($hauling->getRoutes()->toArray());
        $em->persist($hauling);
        $em->flush();

        return $this->redirectToRoute('APP_HOMEPAGE');
    }
}
