<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Company;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\ProjectComplexity;
use AppBundle\Entity\ProjectScope;
use AppBundle\Entity\ProjectStatus;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
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
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.name.not_blank',
                    ]),
                ],
            ])
            ->add('number', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.project.number.not_blank',
                    ]),
                    new Length([
                        'max' => 128,
                        'maxMessage' => 'validation.constraints.project.number.length',
                    ]),
                ],
            ])
            ->add('sponsor', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'admin.user.sponsor.choice',
                'translation_domain' => 'admin',
            ])
            ->add('manager', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'admin.user.manager.choice',
                'translation_domain' => 'admin',
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.company.choice',
                'translation_domain' => 'admin',
            ])
            ->add('projectComplexity', EntityType::class, [
                'class' => ProjectComplexity::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project_complexity.choice',
                'translation_domain' => 'admin',
            ])
            ->add('projectCategory', EntityType::class, [
                'class' => ProjectCategory::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project_category.choice',
                'translation_domain' => 'admin',
            ])
            ->add('projectScope', EntityType::class, [
                'class' => ProjectScope::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project_scope.choice',
                'translation_domain' => 'admin',
            ])
            ->add('status', EntityType::class, [
                'class' => ProjectStatus::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project_status.choice',
                'translation_domain' => 'admin',
            ])
            ->add('portfolio', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.portfolio.choice',
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
            'data_class' => Project::class,
        ]);
    }
}
