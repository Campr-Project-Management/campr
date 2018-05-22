<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\WorkPackageStatus;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;

class PhaseType extends BaseType
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
                'content',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'scheduledStartAt',
                DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'scheduledFinishAt',
                DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'responsibility',
                EntityType::class,
                [
                    'class' => User::class,
                ]
            )
            ->add(
                'workPackageStatus',
                EntityType::class,
                [
                    'class' => WorkPackageStatus::class,
                ]
            )
            ->add(
                'parent',
                EntityType::class,
                [
                    'class' => WorkPackage::class,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => WorkPackage::class,
                'allow_extra_fields' => true,
            ])
        ;
    }
}
