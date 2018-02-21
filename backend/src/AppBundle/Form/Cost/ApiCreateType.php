<?php

namespace AppBundle\Form\Cost;

use Symfony\Component\Form\FormBuilderInterface;

class ApiCreateType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('project')
            ->remove('workPackage')
        ;
    }
}
