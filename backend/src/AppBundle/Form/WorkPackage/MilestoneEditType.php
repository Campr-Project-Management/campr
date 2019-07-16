<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\WorkPackageStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class MilestoneEditType extends MilestoneCreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'workPackageStatus',
                EntityType::class,
                [
                    'class' => WorkPackageStatus::class,
                ]
            )
        ;
    }
}
