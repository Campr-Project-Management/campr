<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Company;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Programme;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\ProjectComplexity;
use AppBundle\Entity\ProjectScope;
use AppBundle\Entity\ProjectStatus;
use AppBundle\Form\ProjectModule\CreateType as ProjectModuleCreateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CreateType extends AbstractType
{
    private $tokenStorage;

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
        /** @var Project $entity */
        $entity = $builder->getData();
        $self = $this;

        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('number', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.number',
                    ]),
                    new Length([
                        'max' => 128,
                        'maxMessage' => 'length.number',
                    ]),
                ],
            ])
            ->add('logoFile', VichImageType::class, [
                'required' => false,
                'download_link' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'invalid.image',
                    ]),
                ],
            ])
            ->add('programme', EntityType::class, [
                'class' => Programme::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.programme',
                'translation_domain' => 'messages',
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.company_choose',
                'translation_domain' => 'messages',
            ])
            ->add('projectComplexity', EntityType::class, [
                'class' => ProjectComplexity::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($self, $entity) {
                    return $self->findRelatedEntities($er, $entity);
                },
                'placeholder' => 'placeholder.project_complexityo',
                'translation_domain' => 'messages',
            ])
            ->add('projectCategory', EntityType::class, [
                'class' => ProjectCategory::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($self, $entity) {
                    return $self->findRelatedEntities($er, $entity);
                },
                'placeholder' => 'placeholder.project_category',
                'translation_domain' => 'messages',
            ])
            ->add('projectScope', EntityType::class, [
                'class' => ProjectScope::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($self, $entity) {
                    return $self->findRelatedEntities($er, $entity);
                },
                'placeholder' => 'placeholder.project_scope',
                'translation_domain' => 'messages',
            ])
            ->add('status', EntityType::class, [
                'class' => ProjectStatus::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($self, $entity) {
                    return $self->findRelatedEntities($er, $entity);
                },
                'placeholder' => 'placeholder.project_status',
                'translation_domain' => 'messages',
            ])
            ->add('portfolio', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.portfolio',
                'translation_domain' => 'messages',
            ])
            ->add('configuration')
            ->add('projectModules', CollectionType::class, [
                'entry_type' => ProjectModuleCreateType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
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
                            : $data->removeUserFavorite($this->tokenStorage->getToken()->getUser())
                        ;
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
        $resolver->setDefaults([
            'data_class' => Project::class,
            'allow_extra_fields' => true,
        ]);
    }

    private function findRelatedEntities(EntityRepository $er, Project $entity)
    {
        $qb = $er->createQueryBuilder('q');
        $qb->where($qb->expr()->isNull('q.project'));

        if ($entity->getId()) {
            $qb->orWhere($qb->expr()->eq('q.project', $entity->getId()));
        }

        return $qb;
    }
}
