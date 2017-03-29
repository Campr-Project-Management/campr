<?php

namespace AppBundle\Form\Assignment;

use AppBundle\Entity\Assignment;
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

class BaseCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('workPackageProjectWorkCostType', EntityType::class, [
                'class' => WorkPackageProjectWorkCostType::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.wppcwct',
                'translation_domain' => 'messages',
            ])
            ->add('milestone', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.milestone',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.milestone',
                    ]),
                ],
            ])
            ->add('percentWorkComplete', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.work_percent',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.work_percent',
                    ]),
                ],
                'data' => 0,
            ])
            ->add('confirmed', CheckboxType::class)
            ->add('startedAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('finishedAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
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
