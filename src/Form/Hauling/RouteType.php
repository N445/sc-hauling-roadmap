<?php

namespace App\Form\Hauling;

use App\Entity\Hauling\Hauling;
use App\Entity\Hauling\Route;
use App\Entity\Location;
use App\Repository\LocationRepository;
use Arkounay\Bundle\UxCollectionBundle\Form\UxCollectionType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteType extends AbstractType
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

//        $locations = [];
//        foreach ($this->locationRepository->findAll() as $location) {
//            $locations[] = implode('>', $this->locationRepository->getPath($location));
//        }

        $builder
            ->add('fromLocation', EntityType::class, [
                'class'         => Location::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('l')
                              ->where('l.lvl > 0')
                    ;
                },
                'choice_label'  => function (Location $location) {
                    return implode(' > ', $this->locationRepository->getPath($location));
                },
                'attr'          => [
                    'data-controller' => 'select2',
                ],
            ])
            ->add('toLocation', EntityType::class, [
                'class'         => Location::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('l')
                              ->where('l.lvl > 0')
                    ;
                },
                'choice_label'  => function (Location $location) {
                    return implode(' > ', $this->locationRepository->getPath($location));
                },
                'attr'          => [
                    'data-controller' => 'select2',
                ],
            ])
            ->add('fromSpecifiqueLocation', ChoiceType::class, [
                'required' => false,
                'choices'  => $builder->has('fromSpecifiqueLocation') ?
                    [
                        $builder->get('fromSpecifiqueLocation')->getData() => $builder->get('fromSpecifiqueLocation')->getData(),
                    ]
                    : [],
                'attr'     => [
                    'data-controller'              => 'select2-tag',
                    'data-select2-tag-group-value' => 'specifiqueLocation',
                ],
            ])
            ->add('toSpecifiqueLocation', ChoiceType::class, [
                'required' => false,
                'choices'  => $builder->has('toSpecifiqueLocation') ?
                    [
                        $builder->get('toSpecifiqueLocation')->getData() => $builder->get('toSpecifiqueLocation')->getData(),
                    ]
                    : [],
                'attr'     => [
                    'data-controller'              => 'select2-tag',
                    'data-select2-tag-group-value' => 'specifiqueLocation',
                ],
            ])
            ->add('cargos', UxCollectionType::class, [
                'entry_type'           => CargoType::class,
                'allow_add'            => true,
                'allow_delete'         => true,
                'allow_drag_and_drop'  => true,
                'by_reference'         => false,
                'drag_and_drop_filter' => 'input,textarea,a,button,label',
                'display_sort_buttons' => true,
                'add_label'            => 'Add an cargo',
            ])
        ;

        $builder->get('fromSpecifiqueLocation')->resetViewTransformers();
        $builder->get('toSpecifiqueLocation')->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'   => Route::class,
                'block_prefix' => 'custom_route',
            ],
        );
    }
}
