<?php

namespace AppBundle\Form\Decision;

use AppBundle\Entity\Decision;
use AppBundle\Entity\DecisionCategory;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                'decisionCategory',
                EntityType::class,
                [
                    'class' => DecisionCategory::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.decision_category',
                    'translation_domain' => 'messages',
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
                'project',
                EntityType::class,
                [
                    'class' => Project::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.project',
                    'translation_domain' => 'messages',
                ]
            )
            ->add(
                'meeting',
                EntityType::class,
                [
                    'class' => Meeting::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.meeting',
                    'translation_domain' => 'messages',
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
            ->add(
                'done',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'label.done',
                ]
            )
            ->add('showInStatusReport', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Decision::class,
                'allow_extra_fields' => true,
                'csrf_protection' => false,
            ]
        );
    }
}
