<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\Calendar;
use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\User;
use AppBundle\Entity\ColorStatus;
use Symfony\Component\Validator\Constraints\Regex;

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
                        'message' => 'not_blank.puid',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.workpackage',
                'translation_domain' => 'messages',
            ])
            ->add('colorStatus', EntityType::class, [
                'class' => ColorStatus::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.color_status',
                'translation_domain' => 'messages',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.calendar',
                'translation_domain' => 'messages',
            ])
            ->add('progress', TextType::class, [
                'required' => true,
                'data' => 0,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.progress',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'invalid.progress',
                    ]),
                ],
            ])
            ->add('scheduledStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('scheduledFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('forecastStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('forecastFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('actualStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('actualFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('results', TextareaType::class, [
                'required' => false,
            ])
            ->add('isKeyMilestone', CheckboxType::class)
        ;

        $labelModifier = function (FormInterface $form, $project = null) {
            $form->add('labels', EntityType::class, [
                'class' => Label::class,
                'choice_label' => 'title',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) use ($project) {
                    return $er
                        ->createQueryBuilder('l')
                        ->where('l.project = :project')
                        ->setParameter('project', $project)
                    ;
                },
            ]);
        };

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($labelModifier) {
                    $data = $event->getData();
                    $labelModifier($event->getForm(), $data->getProject());
                }
            )
        ;

        $builder
            ->get('project')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($labelModifier) {
                    $data = $event->getForm()->getData();
                    $labelModifier($event->getForm()->getParent(), $data);
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
