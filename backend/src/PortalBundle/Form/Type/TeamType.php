<?php

namespace PortalBundle\Form\Type;

use AppBundle\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TeamType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.workspace.name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'slug',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.workspace.name',
                            ]
                        ),
                    ],
                ]
            )
            ->add('logoUrl', TextType::class)
            ->add('enabled', CheckboxType::class)
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            )
            ->add('uuid', TextType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, $this->getPreSetDataListener())
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Team::class,
                'attr' => [
                    'novalidate' => 'novalidate',
                ],
            ]
        );
    }

    /**
     * @return \Closure
     */
    private function getPreSetDataListener(): \Closure
    {
        return function (FormEvent $event) {
            /** @var Team $team */
            $team = $event->getData();
            if (!$team || !$team->getId()) {
                return;
            }

            $form = $event->getForm();
            $form->remove('uuid');
        };
    }
}
