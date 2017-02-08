<?php

namespace AppBundle\Form\ProjectUser;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectTeam;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.user',
                    ]),
                ],
            ])
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
            ->add('projectCategory', EntityType::class, [
                'class' => ProjectCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_category',
                'translation_domain' => 'messages',
            ])
            ->add('projectRole', EntityType::class, [
                'class' => ProjectRole::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_role',
                'translation_domain' => 'messages',
            ])
            ->add('projectDepartment', EntityType::class, [
                'class' => ProjectDepartment::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_department',
                'translation_domain' => 'messages',
            ])
            ->add('projectTeam', EntityType::class, [
                'class' => ProjectTeam::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project_team',
                'translation_domain' => 'messages',
            ])
            ->add('showInResources', CheckboxType::class)
            ->add('showInRaci', CheckboxType::class)
            ->add('showInOrg', CheckboxType::class)
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
