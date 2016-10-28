<?php

namespace AppBundle\Form\Risk;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Risk;
use AppBundle\Entity\User;
use AppBundle\Entity\Status;
use AppBundle\Entity\Impact;
use AppBundle\Entity\RiskStrategy;
use AppBundle\Entity\RiskCategory;

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
                        'message' => 'validation.constraints.risk.title.not_blank',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.description.not_blank',
                    ]),
                ],
            ])
            ->add('impact', EntityType::class, [
                'class' => Impact::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('cost', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.cost.not_blank',
                    ]),
                ],
            ])
            ->add('budget', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.budget.not_blank',
                    ]),
                ],
            ])
            ->add('delay', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.delay.not_blank',
                    ]),
                ],
            ])
            ->add('priority', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.priority.not_blank',
                    ]),
                ],
            ])
            ->add('riskStrategy', EntityType::class, [
                'class' => RiskStrategy::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('riskCategory', EntityType::class, [
                'class' => RiskCategory::class,
                'required' => false,
                'choice_label' => 'name',
            ])
            ->add('measure', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.risk.measure.not_blank',
                    ]),
                ],
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
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
        ]);
    }
}
