<?php

namespace AppBundle\Form\WorkPackageProjectWorkCostType;

use AppBundle\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\ProjectWorkCostType;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('workPackage', EntityType::class, [
                'class' => WorkPackage::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage',
                'translation_domain' => 'messages',
            ])
            ->add('projectWorkCostType', EntityType::class, [
                'class' => ProjectWorkCostType::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_work_cost_type',
                'translation_domain' => 'messages',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.calendar',
                'translation_domain' => 'messages',
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
            ->add('isGeneric', CheckboxType::class)
            ->add('isInactive', CheckboxType::class)
            ->add('isEnterprise', CheckboxType::class)
            ->add('isCostResource', CheckboxType::class)
            ->add('isBudget', CheckboxType::class)
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
