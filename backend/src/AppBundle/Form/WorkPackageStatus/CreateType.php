<?php

namespace AppBundle\Form\WorkPackageStatus;

use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'code',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'sequence',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.sequence',
                            ]
                        ),
                        new Regex(
                            [
                                'pattern' => '/^([1-9]+\d*)$|^0$/',
                                'message' => 'invalid.sequence',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'project',
                EntityType::class,
                [
                    'class' => Project::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.project',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('visible', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkPackageStatus::class,
        ]);
    }
}
