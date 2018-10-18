<?php

namespace AppBundle\Form\ProjectModule\Analysis;

use Component\ProjectModule\Analysis\ProjectBudgetModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectDurationModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectInnovationDegreeModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectMembersModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectStrategicalMeaningModuleAnalyser;
use Component\ProjectModule\Analysis\ProjectTechnologyComplexityModuleAnalyser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecommendModulesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                ProjectDurationModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(
                            [
                                'value' => ProjectDurationModuleAnalyser::MIN_VALUE,
                            ]
                        ),
                        new LessThanOrEqual(
                            [
                                'value' => ProjectDurationModuleAnalyser::MAX_VALUE,
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                ProjectBudgetModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(
                            [
                                'value' => ProjectBudgetModuleAnalyser::MIN_VALUE,
                            ]
                        ),
                        new LessThanOrEqual(
                            [
                                'value' => ProjectBudgetModuleAnalyser::MAX_VALUE,
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                ProjectMembersModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new GreaterThanOrEqual(
                            [
                                'value' => ProjectMembersModuleAnalyser::MIN_VALUE,
                            ]
                        ),
                        new LessThanOrEqual(
                            [
                                'value' => ProjectMembersModuleAnalyser::MAX_VALUE,
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                ProjectStrategicalMeaningModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Choice(
                            [
                                'choices' => [
                                    ProjectStrategicalMeaningModuleAnalyser::VALUE_LOW,
                                    ProjectStrategicalMeaningModuleAnalyser::VALUE_MEDIUM,
                                    ProjectStrategicalMeaningModuleAnalyser::VALUE_HIGH,
                                ],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                ProjectInnovationDegreeModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Choice(
                            [
                                'choices' => [
                                    ProjectInnovationDegreeModuleAnalyser::VALUE_ENHANCEMENTS,
                                    ProjectInnovationDegreeModuleAnalyser::VALUE_INCREMENTAL,
                                    ProjectInnovationDegreeModuleAnalyser::VALUE_STRATEGIC_INNOVATION,
                                    ProjectInnovationDegreeModuleAnalyser::VALUE_RADICAL_INNOVATION,
                                ],
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                ProjectTechnologyComplexityModuleAnalyser::TYPE,
                IntegerType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(),
                        new Choice(
                            [
                                'choices' => [
                                    ProjectTechnologyComplexityModuleAnalyser::VALUE_LOW,
                                    ProjectTechnologyComplexityModuleAnalyser::VALUE_MEDIUM,
                                    ProjectTechnologyComplexityModuleAnalyser::VALUE_HIGH,
                                ],
                            ]
                        ),
                    ],
                ]
            )
        ;
    }
}
