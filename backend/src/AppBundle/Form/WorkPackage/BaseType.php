<?php

namespace AppBundle\Form\WorkPackage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;

class BaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
                'type',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => [
                        'choices.phase' => WorkPackage::TYPE_PHASE,
                        'choices.milestone' => WorkPackage::TYPE_MILESTONE,
                        'choices.task' => WorkPackage::TYPE_TASK,
                    ],
                    'placeholder' => 'placeholder.type',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.type',
                            ]
                        ),
                    ],
                    'translation_domain' => 'messages',
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => WorkPackage::class,
                'csrf_protection' => false,
            ]
        );
    }
}
