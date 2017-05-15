<?php

namespace AppBundle\Form\SubteamMember;

use AppBundle\Entity\Subteam;
use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
                'required' => true,
            ])
            ->add('subteam', EntityType::class, [
                'class' => Subteam::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.subteam',
                'translation_domain' => 'messages',
            ])
            ->add('isLead', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubteamMember::class,
        ]);
    }
}
