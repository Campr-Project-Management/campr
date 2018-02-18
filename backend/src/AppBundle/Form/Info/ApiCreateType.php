<?php

namespace AppBundle\Form\Info;

use AppBundle\Form\Info\BaseCreateType as IBCT;
use Symfony\Component\Form\FormBuilderInterface;

class ApiCreateType extends IBCT
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->remove('project');
    }
}
