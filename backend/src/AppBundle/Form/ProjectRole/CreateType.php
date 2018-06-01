<?php

namespace AppBundle\Form\ProjectRole;

use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
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
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.name',
                    ]),
                ],
            ])
            ->add('sequence', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.sequence',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$|^0$/',
                        'message' => 'invalid.sequence',
                    ]),
                ],
            ])
            ->add('isLead', CheckboxType::class)
            ->add('project', EntityType::class, [
                'required' => false,
                'class' => Project::class,
                'choice_label' => 'name',
                'placeholder' => 'placeholder.project',
                'translation_domain' => 'messages',
            ])
        ;

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $projectRole = $event->getData();
                    $form = $event->getForm();

                    $qb = function (EntityRepository $er) use ($projectRole) {
                        $qb = $er->createQueryBuilder('pr');
                        if ($projectRole instanceof ProjectRole and $projectRole->getId()) {
                            $qb
                                ->andWhere('pr.id != :projectRoleId')
                                ->andWhere('pr.parent != :parentId OR pr.parent IS NULL')
                                ->setParameter('projectRoleId', $projectRole->getId())
                                ->setParameter('parentId', $projectRole->getId())
                            ;
                        }

                        return $qb;
                    };

                    $form->add('parent', EntityType::class, [
                        'class' => ProjectRole::class,
                        'required' => false,
                        'choice_label' => 'name',
                        'placeholder' => 'placeholder.project_role',
                        'translation_domain' => 'messages',
                        'choice_translation_domain' => 'messages',
                        'query_builder' => $qb,
                    ]);
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
            'data_class' => ProjectRole::class,
        ]);
    }
}
