<?php

namespace AppBundle\Form\ColorStatus;

use AppBundle\Entity\ColorStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'color',
                ChoiceType::class,
                [
                    'required' => true,
                    'choices' => [
                        'choices.green' => 'green',
                        'choices.yellow' => 'yellow',
                        'choices.red' => 'red',
                    ],
                    'placeholder' => 'placeholder.color_status',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.color',
                            ]
                        ),
                    ],
                    'translation_domain' => 'messages',
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
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ColorStatus::class,
        ]);
    }
}
