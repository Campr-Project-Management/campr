<?php

namespace AppBundle\Form\WorkPackage;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\WorkPackage;

class ApiAttachmentType extends CreateType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'medias',
            CollectionType::class,
            [
                'entry_type' => UploadMediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => [
                    'max_size' => $options['max_media_size'],
                ],
            ]
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['max_media_size']);
        $resolver->setAllowedTypes('max_media_size', 'int');
        $resolver->setDefaults(
            [
                'data_class' => WorkPackage::class,
                'csrf_protection' => false,
                'max_media_size' => 1024 * 1024 * 10,
            ]
        );
    }
}
