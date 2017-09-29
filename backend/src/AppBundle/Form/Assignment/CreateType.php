<?php

namespace AppBundle\Form\Assignment;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('workPackage', EntityType::class, [
                'class' => WorkPackage::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage',
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
            'data_class' => Assignment::class,
        ]);
    }
}
