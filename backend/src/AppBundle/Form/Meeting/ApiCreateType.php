<?php

namespace AppBundle\Form\Meeting;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use AppBundle\Form\MeetingParticipant\CreateType as MeetingParticipantCreateType;
use AppBundle\Form\WorkPackage\UploadMediaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\MeetingObjective\BaseType as ObjectiveType;
use AppBundle\Form\MeetingAgenda\CreateType as AgendaType;
use AppBundle\Form\Decision\CreateType as DecisionType;
use AppBundle\Form\Todo\BaseCreateType as TodoType;
use AppBundle\Form\Info\BaseCreateType as InfoType;

class ApiCreateType extends BaseCreateType
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
                'meetingObjectives',
                CollectionType::class,
                [
                    'entry_type' => ObjectiveType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'meetingAgendas',
                CollectionType::class,
                [
                    'entry_type' => AgendaType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'decisions',
                CollectionType::class,
                [
                    'entry_type' => DecisionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'todos',
                CollectionType::class,
                [
                    'entry_type' => TodoType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'infos',
                CollectionType::class,
                [
                    'entry_type' => InfoType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add(
                'medias',
                CollectionType::class,
                [
                    'entry_type' => UploadMediaType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'max_size' => $options['max_media_size'],
                    ],
                ]
            )
            ->add(
                'meetingParticipants',
                CollectionType::class,
                [
                    'entry_type' => MeetingParticipantCreateType::class,
                    'entry_options' => [
                        'skip_meeting' => true,
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['max_media_size']);
        $resolver->setAllowedTypes('max_media_size', 'int');
        $resolver->setDefaults(
            [
                'data_class' => Meeting::class,
                'allow_extra_fields' => true,
                'max_media_size' => Project::DEFAULT_MAX_UPLOAD_FILE_SIZE,
            ]
        );
    }
}
