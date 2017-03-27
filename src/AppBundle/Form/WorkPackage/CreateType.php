<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;
use AppBundle\Entity\ColorStatus;
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
            ->add('puid', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.puid',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'choices.phase' => WorkPackage::TYPE_PHASE,
                    'choices.milestone' => WorkPackage::TYPE_MILESTONE,
                    'choices.task' => WorkPackage::TYPE_TASK,
                ],
                'placeholder' => 'placeholder.type',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.type',
                    ]),
                ],
                'translation_domain' => 'messages',
            ])
            ->add('parent', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage',
                'translation_domain' => 'messages',
            ])
            ->add('workPackageCategory', EntityType::class, [
                'class' => WorkPackageCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage_category',
                'translation_domain' => 'messages',
            ])
            ->add('colorStatus', EntityType::class, [
                'class' => ColorStatus::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.color_status',
                'translation_domain' => 'messages',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.calendar',
                'translation_domain' => 'messages',
            ])
            ->add('progress', TextType::class, [
                'required' => false,
                'data' => 0,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'invalid.progress',
                    ]),
                ],
            ])
            ->add('scheduledStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('scheduledFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('forecastStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('forecastFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('actualStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('actualFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('results', TextareaType::class, [
                'required' => false,
            ])
            ->add('isKeyMilestone', CheckboxType::class)
        ;

        $formModifier = function (FormInterface $form, $project = null, $wpId = null) {
            $form->add('labels', EntityType::class, [
                'class' => Label::class,
                'choice_label' => 'title',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) use ($project) {
                    $qb = $er->createQueryBuilder('l');

                    return $qb
                        ->where(
                            $qb->expr()->orX(
                                $qb->expr()->eq('l.project', ':project'),
                                $qb->expr()->isNull('l.project')
                            )
                        )
                        ->setParameter('project', $project)
                    ;
                },
            ]);
            $dependencyFieldOptions = [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) use ($project, $wpId) {
                    $qb = $er->createQueryBuilder('wp');
                    $qb->where(
                            $qb->expr()->orX(
                                $qb->expr()->eq('wp.project', ':project'),
                                $qb->expr()->isNull('wp.project')
                            )
                        )
                        ->setParameter('project', $project)
                    ;
                    if ($wpId) {
                        $qb->andWhere('wp.id != :wpId')->setParameter('wpId', $wpId);
                    }

                    return $qb;
                },
            ];
            $form->add('dependencies', EntityType::class, $dependencyFieldOptions);
            $form->add('dependants', EntityType::class, $dependencyFieldOptions);
        };

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getProject(), $data->getId());
                }
            )
        ;

        $builder
            ->get('project')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $form = $event->getForm();
                    $project = $form->getData();
                    $wpId = $form->getParent()->getData()->getId();
                    $formModifier($event->getForm()->getParent(), $project, $wpId);
                }
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => WorkPackage::class,
                'allow_extra_fields' => true,
            ])
        ;
    }
}
