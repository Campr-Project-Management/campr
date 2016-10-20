<?php

namespace AppBundle\Form\WorkPackage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;
use AppBundle\Entity\ColorStatus;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('puid', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.workpackage.pui.not_blank',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.workpackage.name.not_blank',
                    ]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
            ])
            ->add('colorStatus', EntityType::class, [
                'class' => ColorStatus::class,
                'choice_label' => 'name',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('scheduledStartAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('scheduledFinishAt', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WorkPackage::class,
        ]);
    }
}
