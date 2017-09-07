<?php

namespace AppBundle\Form\Lesson;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\ProjectCloseDown;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('projectCloseDown', EntityType::class, [
                'required' => true,
                'class' => ProjectCloseDown::class,
                'choice_label' => function ($projectCloseDown) {
                    return $projectCloseDown->getId().'-'.$projectCloseDown->getProjectName();
                },
                'placeholder' => 'placeholder.project',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.project',
                    ]),
                ],
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
            'data_class' => Lesson::class,
        ]);
    }
}
