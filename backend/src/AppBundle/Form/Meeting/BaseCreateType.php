<?php

namespace AppBundle\Form\Meeting;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\MeetingCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class BaseCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'meetingCategory',
                EntityType::class,
                [
                    'class' => MeetingCategory::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.category',
                    'translation_domain' => 'messages',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.meeting_category',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'distributionLists',
                EntityType::class,
                [
                    'class' => DistributionList::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'location',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.location',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'date',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.date',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'start',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'HH:mm',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.start',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'end',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'HH:mm',
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.end',
                            ]
                        ),
                    ],
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Meeting::class,
                'allow_extra_fields' => true,
            ]
        );
    }
}
