<?php

namespace AppBundle\Form\Risk;

use AppBundle\Entity\Risk;
use AppBundle\Entity\RiskCategory;
use AppBundle\Entity\RiskStrategy;
use AppBundle\Entity\RiskStatus;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use AppBundle\Form\Measure\BaseType as MeasureBaseType;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.title',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.description',
                    ]),
                ],
            ])
            ->add('impact', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.impact',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.impact',
                    ]),
                ],
            ])
            ->add('probability', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.probability',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.probability',
                    ]),
                ],
            ])
            ->add('cost', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.cost',
                    ]),
                ],
            ])
            ->add('currency', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    '$' => 'USD',
                    '€' => 'EUR',
                    '₤' => 'GBP',
                ],
                'placeholder' => 'placeholder.currency',
                'translation_domain' => 'messages',
                'constraints' => [
                    new NotNull([
                        'message' => 'not_null.currency',
                    ]),
                ],
            ])
            ->add('budget', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.budget',
                    ]),
                ],
            ])
            ->add('delay', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.delay',
                    ]),
                ],
            ])
            ->add('delayUnit', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'choices.hours' => 'choices.hours',
                    'choices.days' => 'choices.days',
                    'choices.weeks' => 'choices.weeks',
                    'choices.months' => 'choices.months',
                ],
                'constraints' => [
                    new NotNull([
                        'message' => 'not_null.delay_unit',
                    ]),
                ],
                'placeholder' => 'placeholder.time_unit',
                'translation_domain' => 'messages',
            ])
            ->add('priority', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.priority',
                    ]),
                ],
            ])
            ->add('riskStrategy', EntityType::class, [
                'class' => RiskStrategy::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.risk_strategy',
                'translation_domain' => 'messages',
            ])
            ->add('riskCategory', EntityType::class, [
                'class' => RiskCategory::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.risk_category',
                'translation_domain' => 'messages',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'required' => false,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'required' => false,
            ])
            ->add('riskStatus', EntityType::class, [
                'class' => RiskStatus::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.status',
                'translation_domain' => 'messages',
            ])
            ->add('measures', CollectionType::class, [
                'entry_type' => MeasureBaseType::class,
                'by_reference' => false,
                'allow_add' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Risk::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
