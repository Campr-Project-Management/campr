<?php

namespace AppBundle\Form\Risk;

use AppBundle\Entity\Risk;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Measure\BaseType as MeasureType;

class ApiType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('measures', CollectionType::class, [
                'required' => false,
                'entry_type' => MeasureType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Risk::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
