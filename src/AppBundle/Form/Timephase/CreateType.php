<?php

namespace AppBundle\Form\Timephase;

use AppBundle\Entity\Timephase;
use AppBundle\Entity\Assignment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.timephase.type.not_blank',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('assignment', EntityType::class, [
                'class' => Assignment::class,
                'choice_label' => 'id',
                'translation_domain' => 'admin',
            ])
            ->add('unit', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.timephase.unit.not_blank',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('value', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.timephase.value.not_blank',
                    ]),
                ],
            ])
            ->add('startedAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('finishedAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Timephase::class,
        ]);
    }
}
