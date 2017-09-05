<?php

namespace AppBundle\Form\StatusReportConfig;

use AppBundle\Entity\Project;
use AppBundle\Entity\StatusReportConfig;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
            ->add('perDay', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.per_day',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.per_day',
                    ]),
                ],
            ])
            ->add('minutesInterval', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.minutes_interval',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.minutes_interval',
                    ]),
                ],
            ])
            ->add('isDefault', CheckboxType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StatusReportConfig::class,
        ]);
    }
}
