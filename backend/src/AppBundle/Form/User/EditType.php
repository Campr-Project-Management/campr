<?php

namespace AppBundle\Form\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.first_name',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.last_name',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => false,
            ])
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
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'download_link' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'invalid.image',
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, array(
                'expanded' => true,
                'multiple' => true,
                'choices' => [
                    'label.role.user' => 'ROLE_USER',
                    'label.role.admin' => 'ROLE_ADMIN',
                    'label.role.superadmin' => 'ROLE_SUPER_ADMIN',
                ],
                'translation_domain' => 'messages',
            ))
            ->add('isEnabled', CheckboxType::class)
            ->add('isSuspended', CheckboxType::class)
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
