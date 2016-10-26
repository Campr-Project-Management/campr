<?php

namespace AppBundle\Form\WorkingTime;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkingTime;
use AppBundle\Entity\Day;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('day', EntityType::class, [
                'class' => Day::class,
                'choice_label' => 'id',
            ])
            ->add('fromTime', TimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('toTime', TimeType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkingTime::class,
        ]);
    }
}
