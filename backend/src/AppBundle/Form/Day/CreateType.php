<?php

namespace AppBundle\Form\Day;

use AppBundle\Entity\Day;
use AppBundle\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                        'message' => 'not_blank.type',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('working', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.working',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                ],
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.calendar',
                'translation_domain' => 'messages',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Day::class,
        ]);
    }
}
