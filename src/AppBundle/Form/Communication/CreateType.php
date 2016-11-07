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
                'translation_domain' => 'admin',
            ])
            ->add('meetingName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.communication.meeting_name.not_blank',
                    ]),
                ],
            ])
            ->add('participants', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'translation_domain' => 'admin',
                'multiple' => true,
            ])
            ->add('location', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.communication.location.not_blank',
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.communication.content.not_blank',
                    ]),
                ],
            ])
            ->add('schedule', EntityType::class, [
                'class' => Schedule::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
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
