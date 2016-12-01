<?php

namespace AppBundle\Form\MeetingParticipant;

use AppBundle\Entity\MeetingParticipant;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('meeting', EntityType::class, [
                'class' => Meeting::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.meeting.choice',
                'translation_domain' => 'admin',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting_participant.meeting.not_blank',
                    ]),
                ],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'admin.user.choice',
                'translation_domain' => 'admin',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting_participant.user.not_blank',
                    ]),
                ],
            ])
            ->add('remark', TextType::class, [
                'required' => false,
            ])
            ->add('isExcused', CheckboxType::class)
            ->add('isPresent', CheckboxType::class)
            ->add('inDistributionList', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeetingParticipant::class,
        ]);
    }
}
