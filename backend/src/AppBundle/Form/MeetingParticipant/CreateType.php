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
        if ($options['skip_meeting'] !== true) {
            $builder
                ->add('meeting', EntityType::class, [
                    'class' => Meeting::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.meeting',
                    'translation_domain' => 'messages',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'not_blank.meeting',
                        ]),
                    ],
                ])
            ;
        }

        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.meeting_participant.user',
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
            'skip_meeting' => false,
        ]);

        $resolver->setAllowedTypes('skip_meeting', 'bool');
    }
}
