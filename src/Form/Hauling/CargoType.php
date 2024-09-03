<?php

namespace App\Form\Hauling;

use App\Entity\Commodity;
use App\Entity\Hauling\Cargo;
use App\Entity\Hauling\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CargoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('commodity', EntityType::class, [
                'class' => Commodity::class,
                'choice_label' => 'title',
                'attr'         => [
                    'data-controller' => 'select2',
                ],
            ])
            ->add('quantity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cargo::class,
        ]);
    }
}
