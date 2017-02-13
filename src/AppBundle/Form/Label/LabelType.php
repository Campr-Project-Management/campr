<?php

namespace AppBundle\Form\Label;

use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LabelType extends AbstractType
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
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.label.title.not_blank',
                    ]),
                ],
            ])
            ->add('project', EntityType::class, [
                'required' => true,
                'class' => Project::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.label.project.not_blank',
                    ]),
                ],
                'placeholder' => 'admin.project.choice',
                'translation_domain' => 'admin',
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('color', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.label.color.not_blank',
                    ]),
                    new Length([
                        'max' => 6,
                        'maxMessage' => 'validation.constraints.label.color.length',
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
            'data_class' => Label::class,
        ]);
    }
}
