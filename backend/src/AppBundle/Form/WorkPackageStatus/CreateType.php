<?php

namespace AppBundle\Form\WorkPackageStatus;

use AppBundle\Entity\WorkPackageStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add(
                'code',
                TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.name',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'sequence',
                TextType::class,
                [
                    'required' => true,
                    'constraints' => [
                        new NotBlank(
                            [
                                'message' => 'not_blank.sequence',
                            ]
                        ),
                        new Regex(
                            [
                                'pattern' => '/^([1-9]+\d*)$|^0$/',
                                'message' => 'invalid.sequence',
                            ]
                        ),
                    ],
                ]
            )
            ->add('visible', CheckboxType::class)
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    if (isset($data['name']) && !isset($data['code'])) {
                        $data['code'] = $data['name'];
                    }
                    $event->setData($data);
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
            'data_class' => WorkPackageStatus::class,
        ]);
    }
}
