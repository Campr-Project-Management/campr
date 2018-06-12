<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Project;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\ProjectModule\CreateType as ProjectModuleCreateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ApiType extends CreateType
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * ApiType constructor.
     *
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('configuration')
            ->add(
                'projectModules',
                CollectionType::class,
                [
                    'required' => false,
                    'entry_type' => ProjectModuleCreateType::class,
                    'allow_add' => true,
                    'by_reference' => false,
                ]
            )
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    if (array_key_exists('favorite', $data)) {
                        $form = $event->getForm();
                        $form->add('favorite', CheckboxType::class, ['mapped' => false])->setData($data['favorite']);
                    }
                }
            )
            ->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();
                    if ($form->has('favorite')) {
                        $form->get('favorite')->getData()
                            ? $data->addUserFavorite($this->tokenStorage->getToken()->getUser())
                            : $data->removeUserFavorite($this->tokenStorage->getToken()->getUser());
                    }
                }
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Project::class,
                'allow_extra_fields' => true,
                'csrf_protection' => false,
            ]
        );
    }
}
