<?php

namespace AppBundle\Form\Subteam;

use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\SubteamRole;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubteamMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'required' => true,
            ])
            ->add('subteamRoles', EntityType::class, [
                'class' => SubteamRole::class,
                'required' => true,
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubteamMember::class,
        ]);
    }
}
