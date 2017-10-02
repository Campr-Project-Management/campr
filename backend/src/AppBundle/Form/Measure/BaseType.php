<?php

namespace AppBundle\Form\Measure;

use AppBundle\Entity\Measure;
use AppBundle\Entity\Opportunity;
use AppBundle\Entity\Risk;
use AppBundle\Entity\User;
use AppBundle\Form\WorkPackage\UploadMediaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class BaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.title',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.description',
                    ]),
                ],
            ])
            ->add('cost', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.cost',
                    ]),
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'greater_than_or_equal.cost',
                    ]),
                ],
            ])
            ->add('responsibility', EntityType::class, [
                'class' => User::class,
                'required' => false,
                'choice_label' => 'username',
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('risk', EntityType::class, [
                'class' => Risk::class,
                'required' => false,
                'choice_label' => 'title',
                'placeholder' => 'placeholder.risk',
                'translation_domain' => 'messages',
            ])
            ->add('opportunity', EntityType::class, [
                'class' => Opportunity::class,
                'required' => false,
                'choice_label' => 'title',
                'placeholder' => 'placeholder.opportunity',
                'translation_domain' => 'messages',
            ])
            ->add('medias', CollectionType::class, [
                'entry_type' => UploadMediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Measure::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ]);
    }
}
