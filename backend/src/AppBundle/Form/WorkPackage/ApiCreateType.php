<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\Media;
use AppBundle\Form\Cost\ApiCreateType as CostCreateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;

class ApiCreateType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'costs',
                CollectionType::class,
                [
                    'entry_type' => CostCreateType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add('children', ChildrenType::class)
            ->add(
                'medias',
                EntityType::class,
                [
                    'class' => Media::class,
                    'multiple' => true,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => WorkPackage::class,
                'csrf_protection' => false,
                'entity_manager' => null,
                'validation_groups' => ['Default', 'create'],
            ]
        );
    }
}
