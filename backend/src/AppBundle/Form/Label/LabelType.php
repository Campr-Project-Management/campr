<?php

namespace AppBundle\Form\Label;

use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LabelType extends BaseLabelType
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
                'required' => true,
                'class' => Project::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.project',
                    ]),
                ],
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
            'data_class' => Label::class,
            'allow_extra_fields' => true,
        ]);
    }
}
