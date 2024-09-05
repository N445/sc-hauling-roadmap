<?php

namespace App\Form\Hauling;

use App\Entity\Hauling\Hauling;
use Arkounay\Bundle\UxCollectionBundle\Form\UxCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HaulingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('routes', UxCollectionType::class, [
            'entry_type'           => RouteType::class,
            'by_reference'         => false,
            'allow_add'            => true,
            'allow_delete'         => true,
            'allow_drag_and_drop'  => true,
            'drag_and_drop_filter' => 'input,textarea,a,button,label',
            'display_sort_buttons' => true,
            'add_label'            => 'Add an route',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                                   'data_class' => Hauling::class,
                               ]);
    }
}
