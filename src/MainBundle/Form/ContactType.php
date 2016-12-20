<?php

namespace MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('full_name', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.contact.full_name.not_blank',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'constraints' => [
                    new Email([
                        'message' => 'validation.constraints.contact.email.email',
                    ]),
                    new NotBlank([
                        'message' => 'validation.constraints.contact.email.not_blank',
                    ]),
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.contact.subject.not_blank',
                    ]),
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.contact.message.not_blank',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
