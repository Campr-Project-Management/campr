<?php

namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;

class EditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.first_name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.last_name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'company',
                TextType::class,
                [
                    'required' => false,
                    'translation_domain' => 'messages',
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'expanded' => false,
                    'multiple' => false,
                    'choices' => $this->getRoleChoices(),
                    'translation_domain' => 'messages',
                ]
            )
            ->add('isEnabled', CheckboxType::class)
            ->add('isSuspended', CheckboxType::class);

        $builder
            ->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($value) {
                        if (!$value) {
                            return $value;
                        }

                        return array_shift($value);
                    },
                    function ($value) {
                        if (!$value) {
                            return [];
                        }

                        return (array) $value;
                    }
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
            ]
        );
    }

    /**
     * @return string[]
     */
    private function getRoleChoices(): array
    {
        return [
            User::ROLE_USER => User::ROLE_USER,
            User::ROLE_ADMIN => User::ROLE_ADMIN,
            User::ROLE_SUPER_ADMIN => User::ROLE_SUPER_ADMIN,
        ];
    }
}
