<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Form\Cost\ApiCreateType as CostCreateType;
use AppBundle\Form\WorkPackage\BaseType as WorkPackageBaseType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;

class ApiEditType extends ApiCreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('scheduledStartAt')
            ->remove('scheduledFinishAt')
            ->remove('actualStartAt')
            ->remove('actualFinishAt')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => WorkPackage::class,
                'csrf_protection' => false,
                'validation_groups' => ['Default', 'edit'],
                'allow_extra_fields' => true,
            ]
        );
    }
}
