<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use AppBundle\Entity\WorkPackageCategory;
use AppBundle\Entity\WorkPackageStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;
use AppBundle\Entity\ColorStatus;
use Symfony\Component\Validator\Constraints\Regex;

class CreateType extends BaseType
{
    private $em;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        if (isset($options['entity_manager'])) {
            $this->em = $options['entity_manager'];
        }

        $builder
            ->add('workPackageStatus', EntityType::class, [
                'class' => WorkPackageStatus::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.status',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('q');

                    return $qb->where($qb->expr()->isNull('q.project'));
                },
                'translation_domain' => 'messages',
                'choice_translation_domain' => 'messages',
            ])
            ->add('phase', EntityType::class, [
                'required' => false,
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.phase',
                'translation_domain' => 'messages',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->where(
                        $qb
                            ->expr()
                            ->eq('p.type', WorkPackage::TYPE_PHASE)
                    );

                    return $qb;
                },
            ])
            ->add('milestone', EntityType::class, [
                'required' => false,
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.milestone',
                'translation_domain' => 'messages',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('m');
                    $qb->where(
                        $qb
                            ->expr()
                            ->eq('m.type', WorkPackage::TYPE_MILESTONE)
                    );

                    return $qb;
                },
            ])
            ->add('parent', EntityType::class, [
                'class' => WorkPackage::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage',
                'translation_domain' => 'messages',
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('m');
                    $qb->where(
                        $qb
                            ->expr()
                            ->eq('m.type', WorkPackage::TYPE_TASK)
                    );

                    return $qb;
                },
            ])
            ->add('workPackageCategory', EntityType::class, [
                'class' => WorkPackageCategory::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage_category',
                'translation_domain' => 'messages',
            ])
            ->add('colorStatus', EntityType::class, [
                'class' => ColorStatus::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.color_status',
                'translation_domain' => 'messages',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('accountability', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('supportUsers', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
            ])
            ->add('consultedUsers', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
            ])
            ->add('informedUsers', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'required' => false,
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
            ->add('duration', TextType::class, [
                'required' => false,
                'data' => 0,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'invalid.duration',
                    ]),
                ],
            ])
            ->add('scheduledStartAt', DateType::class, [
                'required' => true,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('scheduledFinishAt', DateType::class, [
                'required' => true,
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
            ->add('duration', IntegerType::class)
            ->add('automaticSchedule', CheckboxType::class)
            ->add('externalActualCost', NumberType::class)
            ->add('externalForecastCost', NumberType::class)
            ->add('internalActualCost', NumberType::class)
            ->add('internalForecastCost', NumberType::class)
        ;

        $formModifier = function (FormInterface $form, $project = null, $wpId = null) {
            $form->add('labels', EntityType::class, [
                'class' => Label::class,
                'required' => false,
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
                'required' => false,
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
                'by_reference' => false,
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
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    $formData = $event->getData();
                    $form = $event->getForm();
                    $wp = $form->getData();
                    if ($wp->getWorkpackageStatus() == null && $this->em !== null) {
                        $formData->setWorkPackageStatus($this
                            ->em
                            ->getRepository(WorkPackageStatus::class)
                            ->find(WorkPackageStatus::PENDING)
                        );
                        $event->setData($formData);
                    }
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
