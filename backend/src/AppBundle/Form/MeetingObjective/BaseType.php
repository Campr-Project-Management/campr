<?php

namespace AppBundle\Form\MeetingObjective;

use AppBundle\Entity\Meeting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\MeetingObjective;

class BaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.objectives',
                    ]),
                ],
            ])
            ->add('meeting', EntityType::class, [
                'class' => Meeting::class,
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.meeting',
                'translation_domain' => 'messages',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeetingObjective::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
