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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('abbreviation', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.abbreviation',
                    ]),
                ],
            ])
            ->add('sequence', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.sequence',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'invalid.sequence',
                    ]),
                ],
            ])
            ->add('rate', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0(?:\.[0-9]{1,2})?)$|^([1-9][0-9]{0,9})$|^([1-9][0-9]{0,7}(?:\.[0-9]{1,2})?)$/',
                        'message' => 'invalid.rate',
                    ]),
                ],
            ])
            ->add('projectWorkCostType', EntityType::class, [
                'class' => ProjectWorkCostType::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_work_cost_type',
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
            'data_class' => ProjectDepartment::class,
        ]);
    }
}
