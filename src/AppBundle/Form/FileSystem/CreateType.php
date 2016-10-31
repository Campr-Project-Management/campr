<?php

namespace AppBundle\Form\FileSystem;

use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateType extends AbstractType
{
    private $adapters;

    public function __construct($adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.filesystem.create.project_placeholder',
                'translation_domain' => 'admin',
            ])
            ->add('driver', ChoiceType::class, [
                'required' => true,
                'choices' => $this->adapters,
                'constraints' => [
                    new NotNull([
                        'message' => 'validation.constraints.filesystem.driver.not_null',
                    ]),
                ],
                'placeholder' => 'admin.filesystem.create.adapter.placeholder',
                'translation_domain' => 'admin',
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.constraints.general_field.name.not_blank',
                    ]),
                ],
            ])
        ;

        $formModifier = function (FormInterface $form, $driver = null) {
            $class = null;
            switch ($driver) {
                case FileSystem::DROPBOX_ADAPTER:
                    $class = DropboxAdapterType::class;
                    break;
                case FileSystem::LOCAL_ADAPTER:
                    $class = LocalAdapterType::class;
                    break;
            }

            if ($class) {
                $form->add('adapter', $class, [
                    'mapped' => false,
                ]);
            }
        };

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getDriver());
                }
            )
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    $config = $form->getData()->getConfig();
                    if ($form->has('adapter')) {
                        $form->get('adapter')->setData($config);
                    }
                }
            )
        ;

        $builder
            ->get('driver')
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $driver = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $driver);
                }
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FileSystem::class,
        ]);
    }
}
