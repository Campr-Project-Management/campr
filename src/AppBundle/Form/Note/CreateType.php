<?php

namespace AppBundle\Form\Note;

use AppBundle\Entity\Note;
use AppBundle\Entity\Project;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\User;
use AppBundle\Entity\Status;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('meeting', EntityType::class, [
                'class' => Meeting::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'translation_domain' => 'admin',
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
