<?php

namespace AppBundle\Form\Info;

use AppBundle\Entity\Info;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project')
            ->add('topic')
            ->add('description')
            ->add('infoStatus')
            ->add('infoCategory')
            ->add('expiryDate', null, [
                'widget' => 'single_text',
            ])
            ->add('users')
        ;
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
