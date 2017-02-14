<?php

namespace MainBundle\Form\PaymentMethod;

use AppBundle\Entity\PaymentMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.payment_method',
                    ]),
                ],
            ])
            ->add('token', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.token',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PaymentMethod::class,
        ]);
    }
}
