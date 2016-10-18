<?php

namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
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

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.user.username.not_blank',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.user.email.not_blank',
                    ]),
                    new Email([
                        'message' => 'validation.constraints.user.email.email',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.constraints.user.password.match',
                'required' => true,
                'constraints' => [
                    new NotBlank(array(
                        'message' => 'validation.constraints.user.password.not_blank',
                    )),
                    new Regex([
                        'pattern' => "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
                        'message' => 'validation.constraints.user.password.regex',
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.user.first_name.not_blank',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.user.last_name.not_blank',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => true,
            ])
            ->add('roles', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'admin.user.edit.role.user' => 'ROLE_USER',
                    'admin.user.edit.role.admin' => 'ROLE_ADMIN',
                    'admin.user.edit.role.superadmin' => 'ROLE_SUPER_ADMIN',
                ],
                'translation_domain' => 'admin',
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
