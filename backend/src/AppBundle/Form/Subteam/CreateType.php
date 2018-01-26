<?php

namespace AppBundle\Form\Subteam;

use AppBundle\Entity\Project;
use AppBundle\Entity\Subteam;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
            ->add('parent', EntityType::class, [
                'class' => Subteam::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.subteam',
                'translation_domain' => 'messages',
            ])
            ->add('subteamMembers', CollectionType::class, [
                'entry_type' => MemberType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Subteam::class,
            'allow_extra_fields' => true,
        ]);
    }
}
