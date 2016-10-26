<?php

namespace AppBundle\Form\Assignment;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\WorkPackageProjectWorkCostType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workPackage', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('workPackageProjectWorkCostType', EntityType::class, [
                'class' => WorkPackageProjectWorkCostType::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('milestone', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.assignment.milestone.not_blank',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('percentWorkComplete', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.assignment.work_percent.not_blank',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('startedAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('finishedAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('confirmed', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Assignment::class,
        ]);
    }
}
