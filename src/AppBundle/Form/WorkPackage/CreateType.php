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
                        'message' => 'validation.constraints.workpackage.puid.not_blank',
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.name.not_blank',
                    ]),
                ],
            ])
            ->add('parent', EntityType::class, [
                'class' => WorkPackage::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.workpackage.choice',
                'translation_domain' => 'admin',
            ])
            ->add('colorStatus', EntityType::class, [
                'class' => ColorStatus::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.color_status.choice',
                'translation_domain' => 'admin',
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
                'placeholder' => 'admin.user.choice',
                'translation_domain' => 'admin',
            ])
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.project.choice',
                'translation_domain' => 'admin',
            ])
            ->add('calendar', EntityType::class, [
                'class' => Calendar::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.calendar.choice',
                'translation_domain' => 'admin',
            ])
            ->add('progress', TextType::class, [
                'required' => true,
                'data' => 0,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.workpackage.progress.not_blank',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'validation.constraints.workpackage.progress.invalid',
                    ]),
                ],
            ])
            ->add('scheduledStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('scheduledFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('forecastStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('forecastFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('actualStartAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('actualFinishAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
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
                'translation_domain' => 'admin',
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
