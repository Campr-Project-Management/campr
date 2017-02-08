<?php

namespace AppBundle\Form\Todo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Status;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project.choice',
                'translation_domain' => 'admin',
            ])
            ->add('meeting', EntityType::class, [
                'class' => Meeting::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.meeting.choice',
                'translation_domain' => 'admin',
            ])
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.todo.title.not_blank',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.todo.description.not_blank',
                    ]),
                ],
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'admin.user.choice',
                'translation_domain' => 'admin',
            ])
            ->add('date', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('dueDate', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.status.choice',
                'translation_domain' => 'admin',
            ])
            ->add('showInStatusReport', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Todo::class,
            'allow_extra_fields' => true,
        ]);
    }
}
