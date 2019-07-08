<?php

namespace AppBundle\Form\ProjectDepartment;

use AppBundle\Entity\ProjectDepartment;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'abbreviation',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.abbreviation',
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
                'rate',
                TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Regex(
                            [
                                'pattern' => '/^(0(?:\.[0-9]{1,2})?)$|^([1-9][0-9]{0,9})$|^([1-9][0-9]{0,7}(?:\.[0-9]{1,2})?)$/',
                                'message' => 'invalid.rate',
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
        $resolver->setDefaults(
            [
                'data_class' => ProjectDepartment::class,
                'allow_extra_fields' => true,
            ]
        );
    }
}
