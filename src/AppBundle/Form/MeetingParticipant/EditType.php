<?php

namespace AppBundle\Form\MeetingParticipant;

use AppBundle\Entity\MeetingParticipant;
use AppBundle\Entity\User;
use AppBundle\Entity\Meeting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('meeting', EntityType::class, [
                'class' => Meeting::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'translation_domain' => 'admin',
            ])
            ->add('remark', TextType::class, [
                'required' => false,
            ])
            ->add('isExcused', CheckboxType::class)
            ->add('isPresent', CheckboxType::class)
            ->add('inDistributionList', CheckboxType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeetingParticipant::class,
        ]);
    }
}
