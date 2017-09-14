<?php

namespace AppBundle\Form\ProjectCloseDown;

use AppBundle\Entity\ProjectCloseDown;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
            ->add('overallImpression', TextareaType::class, [
                'required' => false,
            ])
            ->add('performanceSchedule', TextareaType::class, [
                'required' => false,
            ])
            ->add('organizationContext', TextareaType::class, [
                'required' => false,
            ])
            ->add('projectManagement', TextareaType::class, [
                'required' => false,
            ])
            ->add('frozen', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => ProjectCloseDown::class,
        ]);
    }
}
