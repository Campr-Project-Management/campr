<?php

namespace MainBundle\Form\Team;

use AppBundle\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.team.name.not_blank',
                    ]),
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.team.slug.not_blank',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-z0-9]*([a-z0-9-]+[a-z0-9])?$/iD',
                        'message' => 'validation.constraints.team.slug.invalid',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
