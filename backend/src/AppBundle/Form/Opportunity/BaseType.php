<?php

namespace AppBundle\Form\Opportunity;

use AppBundle\Entity\Opportunity;
use AppBundle\Entity\OpportunityStatus;
use AppBundle\Entity\OpportunityStrategy;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotNull;

class BaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.title',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.description',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'impact',
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.impact',
                            ]
                        ),
                        new GreaterThanOrEqual(
                            [
                                'value' => 0,
                                'message' => 'greater_than_or_equal.impact',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'probability',
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.probability',
                            ]
                        ),
                        new GreaterThanOrEqual(
                            [
                                'value' => 0,
                                'message' => 'greater_than_or_equal.probability',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'costSavings',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.cost_savings',
                            ]
                        ),
                        new GreaterThan(
                            [
                                'value' => 0,
                                'message' => 'greater_than.cost_savings',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'timeSavings',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.time_savings',
                            ]
                        ),
                        new GreaterThan(
                            [
                                'value' => 0,
                                'message' => 'greater_than.time_savings',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'timeUnit',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => [
                        'choices.hours' => 'choices.hours',
                        'choices.days' => 'choices.days',
                        'choices.weeks' => 'choices.weeks',
                        'choices.months' => 'choices.months',
                    ],
                    'constraints' => [
                        new NotNull(
                            [
                                'message' => 'not_null.time_unit',
                            ]
                        ),
                    ],
                    'placeholder' => 'placeholder.time_unit',
                    'translation_domain' => 'messages',
                ]
            )
            ->add(
                'priority',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.priority',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'opportunityStrategy',
                EntityType::class,
                [
                    'class' => OpportunityStrategy::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.name',
                    'translation_domain' => 'messages',
                    'choice_translation_domain' => 'messages',
                ]
            )
            ->add(
                'opportunityStatus',
                EntityType::class,
                [
                    'class' => OpportunityStatus::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.name',
                    'translation_domain' => 'messages',
                    'choice_translation_domain' => 'messages',
                ]
            )
            ->add(
                'dueDate',
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
                    'required' => false,
                    'choice_label' => 'username',
                    'placeholder' => 'placeholder.user',
                    'translation_domain' => 'messages',
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Opportunity::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
