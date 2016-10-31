<?php

namespace AppBundle\Form\WorkPackageProjectWorkCostType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\ProjectWorkCostType;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('workPackage', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
            ])
            ->add('projectWorkCostType', EntityType::class, [
                'class' => ProjectWorkCostType::class,
                'choice_label' => 'name',
            ])
            ->add('base', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
            ->add('change', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
            ->add('actual', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
            ->add('remaining', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
            ->add('forecast', NumberType::class, [
                'required' => false,
                'scale' => 2,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkPackageProjectWorkCostType::class,
        ]);
    }
}
