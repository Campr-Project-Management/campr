<?php

namespace AppBundle\Form\ProjectDepartmentMember;

use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectDepartmentMember;
use AppBundle\Entity\ProjectUser;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'projectUser',
                EntityType::class,
                [
                    'class' => ProjectUser::class,
                    'choice_label' => 'userFullName',
                    'placeholder' => 'placeholder.user',
                    'translation_domain' => 'messages',
                    'required' => true,
                ]
            )
            ->add(
                'projectDepartment',
                EntityType::class,
                [
                    'class' => ProjectDepartment::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.subteam',
                    'translation_domain' => 'messages',
                ]
            )
            ->add('lead', CheckboxType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProjectDepartmentMember::class,
            ]
        );
    }
}
