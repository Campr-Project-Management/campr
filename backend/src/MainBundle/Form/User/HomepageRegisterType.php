<?php

namespace MainBundle\Form\User;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HomepageRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
            ])
            ->add('fullName', TextType::class, [
                'required' => true,
            ])
            ->add('projectDuration', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[projectDuration]',
            ])
            ->add('projectBudget', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[projectBudget]',
            ])
            ->add('peopleInvolved', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[peopleInvolved]',
            ])
            ->add('departmentsInvolved', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[departmentsInvolved]',
            ])
            ->add('strategicalMeaning', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[strategicalMeaning]',
            ])
            ->add('risks', TextType::class, [
                'required' => true,
                'property_path' => 'signUpDetails[risks]',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'class' => User::class,
                'validation_groups' => ['HomepageSignUp'],
            ])
        ;
    }
}
