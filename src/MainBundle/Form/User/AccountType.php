<?php

namespace MainBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccountType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'disabled' => true,
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
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
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'download_link' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'validation.constraints.user.avatar.image',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'required' => false,
            ])
            ->add('twitter', TextType::class, [
                'required' => false,
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
            ])
            ->add('gplus', TextType::class, [
                'required' => false,
            ])
            ->add('linkedIn', TextType::class, [
                'required' => false,
            ])
            ->add('medium', TextType::class, [
                'required' => false,
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
