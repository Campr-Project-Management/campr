<?php

namespace AppBundle\Form\Info;

use Symfony\Component\Form\FormBuilderInterface;

class ApiCreateType extends CreateType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('project');
    }
}
