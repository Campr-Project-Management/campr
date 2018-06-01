<?php

namespace AppBundle\Form\Info;

use AppBundle\Entity\Info;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project')
            ->add('meeting')
            ->add('topic')
            ->add('description')
            ->add('infoStatus', null, [
                'choice_translation_domain' => 'messages',
            ])
            ->add('infoCategory', null, [
                'choice_translation_domain' => 'messages',
            ])
            ->add('responsibility')
            ->add('dueDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Info::class,
            'allow_extra_fields' => true,
        ]);
    }
}
