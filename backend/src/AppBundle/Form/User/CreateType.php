<?php

namespace AppBundle\Form\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use AppBundle\Entity\Company;

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
                'username',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.username',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.email',
                            ]
                        ),
                        new Email(
                            [
                                'message' => 'invalid.email',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'plainPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'match.password',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.password',
                            ]
                        ),
                        new Regex(
                            [
                                'pattern' => "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
                                'message' => 'regex.password',
                            ]
                        ),
                    ],
                ]
            )
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
                'phone',
                TextType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'company',
                EntityType::class,
                [
                    'required' => false,
                    'class' => Company::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.company_choose',
                    'translation_domain' => 'messages',
                ]
            )
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => $this->getRoleChoices(),
                    'translation_domain' => 'messages',
                ]
            );

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
                'allow_extra_fields' => true,
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
