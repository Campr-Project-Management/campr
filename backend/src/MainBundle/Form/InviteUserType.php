<?php

namespace MainBundle\Form;

use MainBundle\Validator\Constraints\TeamMember\ActiveMember;
use MainBundle\Validator\Constraints\TeamMember\SelfInvite;
use MainBundle\Validator\Constraints\TeamMember\UserInvited;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class InviteUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $constraints = [
            new Email([
                'message' => 'invalid.email',
            ]),
            new NotBlank([
                'message' => 'not_blank.email',
            ]),
            new SelfInvite([
                'user' => $options['user'],
            ]),
        ];

        if ($options['team']) {
            $constraints[] = new UserInvited([
                'team' => $options['team'],
            ]);
            $constraints[] = new ActiveMember([
                'team' => $options['team'],
                'user' => $options['user'],
            ]);
        }

        $builder
            ->add('email', EmailType::class, [
                'constraints' => $constraints,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'team' => null,
            'user' => null,
        ]);
    }
}
