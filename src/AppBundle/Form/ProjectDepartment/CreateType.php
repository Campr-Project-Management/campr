<?php

namespace AppBundle\Form\ProjectDepartment;

use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectWorkCostType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.name.not_blank',
                    ]),
                ],
            ])
            ->add('abbreviation', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.project_department.abbreviation.not_blank',
                    ]),
                ],
            ])
            ->add('sequence', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.sequence.not_blank'
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'validation.constraints.general_field.sequence.invalid',
                    ]),
                ],
            ])
            ->add('rate', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0(?:\.[0-9]{1,2})?)$|^([1-9][0-9]{0,9})$|^([1-9][0-9]{0,7}(?:\.[0-9]{1,2})?)$/',
                        'message' => 'validation.constraints.project_department.rate.invalid',
                    ]),
                ],
            ])
            ->add('projectWorkCostType', EntityType::class, [
                'class' => ProjectWorkCostType::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project_work_cost_type.choice',
                'translation_domain' => 'admin',
            ])
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectDepartment::class,
        ]);
    }
}
