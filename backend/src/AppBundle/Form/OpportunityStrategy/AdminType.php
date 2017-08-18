<?php

namespace AppBundle\Form\OpportunityStrategy;

use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\OpportunityStrategy;

class AdminType extends BaseType
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
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OpportunityStrategy::class,
        ]);
    }
}
