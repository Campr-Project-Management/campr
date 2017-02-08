<?php

namespace AppBundle\Form\Communication;

use AppBundle\Entity\Communication;
use AppBundle\Entity\Project;
use AppBundle\Entity\Schedule;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'translation_domain' => 'messages',
                'placeholder' => 'placeholder.project',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.project',
                    ]),
                ],
            ])
            ->add('meetingName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.meeting_name',
                    ]),
                ],
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'multiple' => true,
            ])
            ->add('location', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.location',
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.content',
                    ]),
                ],
            ])
            ->add('schedule', EntityType::class, [
                'class' => Schedule::class,
                'choice_label' => 'name',
                'translation_domain' => 'messages',
                'placeholder' => 'placeholder.schedule',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.schedule',
                    ]),
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Communication::class,
        ]);
    }
}
