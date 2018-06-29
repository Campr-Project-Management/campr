<?php

namespace MainBundle\Form\Team;

use AppBundle\Entity\TeamInvite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamInvitesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('teamInvites', EntityType::class, [
                'class' => TeamInvite::class,
                'required' => true,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'id',
            ])
            ->add('action', ChoiceType::class, [
                'choices' => [
                    'resend' => 'resend',
                    'delete' => 'delete',
                ],
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
