<?php

namespace AppBundle\Form\Info;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\Info;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project')
            ->add('meeting')
            ->add('topic')
            ->add('description')
            ->add(
                'infoCategory',
                null,
                [
                    'choice_translation_domain' => 'messages',
                ]
            )
            ->add('responsibility')
            ->add(
                'expiresAt',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd',
                ]
            )
            ->add('distributionList', EntityType::class, [
                'class' => DistributionList::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.distribution_list',
                'translation_domain' => 'messages',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Info::class,
                'allow_extra_fields' => true,
            ]
        );
    }
}
