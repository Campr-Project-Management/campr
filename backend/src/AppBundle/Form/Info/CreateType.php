<?php

namespace AppBundle\Form\Info;

use AppBundle\Entity\Info;
use AppBundle\Form\Info\BaseCreateType as IBCT;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends IBCT
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Info::class,
            ])
        ;
    }
}
