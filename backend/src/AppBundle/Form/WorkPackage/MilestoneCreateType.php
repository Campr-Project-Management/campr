<?php

namespace AppBundle\Form\WorkPackage;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;

class MilestoneCreateType extends BaseType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(
                'content',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'scheduledStartAt',
                DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'scheduledFinishAt',
                DateType::class,
                [
                    'required' => false,
                    'widget' => 'single_text',
                    'format' => 'dd-MM-yyyy',
                ]
            )
            ->add(
                'responsibility',
                EntityType::class,
                [
                    'class' => User::class,
                ]
            )
            ->add(
                'phase',
                EntityType::class,
                [
                    'class' => WorkPackage::class,
                ]
            )
            ->add('isKeyMilestone', CheckboxType::class)
        ;

        $builder
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    if ($form->has('scheduledStartAt')) {
                        $form->get('scheduledStartAt')->setData($form->getData()->getScheduledFinishAt());
                    }
                }
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => WorkPackage::class,
                'allow_extra_fields' => true,
            ])
        ;
    }
}
