<?php

namespace AppBundle\Form\StatusReport;

use AppBundle\Entity\StatusReport;
use AppBundle\Form\TrafficLight\TrafficLightType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projectActionNeeded', CheckboxType::class)
            ->add('projectTrafficLight', TrafficLightType::class)
            ->add('comment', TextType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => StatusReport::class,
                'allow_extra_fields' => true,
            ]
        );
    }
}
