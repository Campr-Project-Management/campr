<?php

namespace AppBundle\Form\Project;

use AppBundle\Entity\Company;
use AppBundle\Entity\Portfolio;
use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectCategory;
use AppBundle\Entity\ProjectComplexity;
use AppBundle\Entity\ProjectScope;
use AppBundle\Entity\ProjectStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CreateType extends AbstractType
{
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
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.company',
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
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
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
