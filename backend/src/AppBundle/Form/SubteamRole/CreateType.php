<?php

namespace AppBundle\Form\SubteamRole;

use AppBundle\Entity\SubteamMember;
use AppBundle\Entity\SubteamRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('subteamMembers', EntityType::class, [
                'class' => SubteamMember::class,
                'required' => true,
                'multiple' => true,
                'attr' => [
                    'class' => 'selectpicker',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubteamRole::class,
        ]);
    }
}
