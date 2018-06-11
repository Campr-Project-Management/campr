<?php

namespace Component\Form\Extension;

use Component\Form\Extension\DataTransformer\BooleanToStringTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class CheckboxTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->resetViewTransformers();
        $builder->addViewTransformer(new BooleanToStringTransformer($options['value']));
    }

    public function getExtendedType()
    {
        return CheckboxType::class;
    }
}
