<?php

namespace MainBundle\Form\TeamMember;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('team', EntityType::class, [
                'class' => Team::class,
                'choice_label' => 'name',
            ])
            ->add('roles', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'admin.user.edit.role.user' => 'ROLE_USER',
                    'admin.user.edit.role.admin' => 'ROLE_ADMIN',
                    'admin.user.edit.role.superadmin' => 'ROLE_SUPER_ADMIN',
                    'admin.user.edit.role.team_member' => 'ROLE_TEAM_MEMBER',
                    'admin.user.edit.role.team_owner' => 'ROLE_TEAM_OWNER',
                ],
                'translation_domain' => 'admin',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TeamMember::class,
        ]);
    }
}
