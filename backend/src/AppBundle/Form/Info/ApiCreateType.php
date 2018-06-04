<?php

namespace AppBundle\Form\Info;

use Symfony\Component\Form\FormBuilderInterface;

class ApiCreateType extends BaseCreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('project');
    }
}
