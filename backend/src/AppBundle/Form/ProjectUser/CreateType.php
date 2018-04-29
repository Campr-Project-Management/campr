<?php

namespace AppBundle\Form\ProjectUser;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Entity\ProjectUser;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends BaseCreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.project',
                    ]),
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $project = null) {
            $form
                ->add('projectRoles', EntityType::class, [
                    'class' => ProjectRole::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'placeholder' => 'placeholder.project_role',
                    'translation_domain' => 'messages',
                    'query_builder' => function (EntityRepository $er) use ($project) {
                        $qb = $er->createQueryBuilder('p');
                        $qb
                            ->where('p.project = :project')
                            ->setParameter('project', $project);

                        return $qb;
                    },
                ])
                ->add('projectCategory', EntityType::class, [
                    'class' => ProjectCategory::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.project_category',
                    'translation_domain' => 'messages',
                    'query_builder' => function (EntityRepository $er) use ($project) {
                        $qb = $er->createQueryBuilder('c');
                        $qb
                            ->where('c.project = :project')
                            ->setParameter('project', $project);

                        return $qb;
                    },
                ])
                ->add('projectDepartments', EntityType::class, [
                    'class' => ProjectDepartment::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'placeholder' => 'placeholder.project_department',
                    'translation_domain' => 'messages',
                    'query_builder' => function (EntityRepository $er) use ($project) {
                        $qb = $er->createQueryBuilder('d');
                        $qb
                            ->where('d.project = :project')
                            ->setParameter('project', $project);

                        return $qb;
                    },
                ])
                ->add('projectTeam', EntityType::class, [
                    'class' => ProjectTeam::class,
                    'required' => false,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.project_team',
                    'translation_domain' => 'messages',
                    'query_builder' => function (EntityRepository $er) use ($project) {
                        $qb = $er->createQueryBuilder('t');
                        $qb
                            ->where('t.project = :project')
                            ->setParameter('project', $project);

                        return $qb;
                    },
                ])
            ;
        };

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getProject());
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
                    $formModifier($event->getForm()->getParent(), $project);
                }
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProjectUser::class,
        ]);
    }
}
