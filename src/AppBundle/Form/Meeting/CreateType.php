<?php

namespace AppBundle\Form\Meeting;

use AppBundle\Entity\Project;
use AppBundle\Entity\Meeting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

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
                'translation_domain' => 'admin',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.name.not_blank',
                    ]),
                ],
            ])
            ->add('location', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting.location.not_blank',
                    ]),
                ],
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting.date.not_blank',
                    ]),
                ],
            ])
            ->add('start', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'H:i:s',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting.start.not_blank',
                    ]),
                ],
            ])
            ->add('end', DateTimeType::class, [
                'widget' => 'single_text',
                'format' => 'H:i:s',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting.end.not_blank',
                    ]),
                ],
            ])
            ->add('objectives', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.meeting.objectives.not_blank',
                    ]),
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class,
            'allow_extra_fields' => true,
        ]);
    }
}
