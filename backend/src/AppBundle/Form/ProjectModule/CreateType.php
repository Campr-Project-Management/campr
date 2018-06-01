<?php

namespace AppBundle\Form\ProjectModule;

use AppBundle\Entity\Enum\ProjectModuleTypeEnum;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectModule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $modules = [];

        foreach (ProjectModuleTypeEnum::ELEMENTS as $key => $elem) {
            $modules[$elem['title']] = $key;
        }

        $builder
            ->add('module', ChoiceType::class, [
                'required' => true,
                'choices' => $modules,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.module',
                    ]),
                    new Choice([
                        'choices' => array_keys(ProjectModuleTypeEnum::ELEMENTS),
                        'message' => 'invalid.module',
                    ]),
                ],
                'translation_domain' => 'messages',
                'choice_translation_domain' => 'messages',
            ])
            ->add('isEnabled', CheckboxType::class)
            ->add('isRequired', CheckboxType::class)
            ->add('isPrint', CheckboxType::class)
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
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
            'data_class' => ProjectModule::class,
        ]);
    }
}
