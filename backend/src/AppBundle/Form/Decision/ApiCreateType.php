<?php

namespace AppBundle\Form\Decision;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Media;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiCreateType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add(
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
                'data_class' => Decision::class,
                'csrf_protection' => false,
                'entity_manager' => null,
                'validation_groups' => ['Default', 'create'],
            ]
        );
    }
}
