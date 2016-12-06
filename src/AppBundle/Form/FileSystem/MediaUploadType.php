<?php

namespace AppBundle\Form\FileSystem;

use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Media;
use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\File as FileConstaint;

class MediaUploadType extends AbstractType
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
        $builder
            ->add('fileSystem', EntityType::class, [
                'class' => FileSystem::class,
                'choice_label' => 'name',
                'placeholder' => 'admin.media.upload.filesystem_placeholder',
                'translation_domain' => 'admin',
                'constraints' => [
                    new NotNull([
                        'message' => 'validation.constraints.media.filesystem.not_null',
                    ]),
                ],
                'query_builder' => function (EntityRepository $er) use ($options) {
                    $qb = $er->createQueryBuilder('fs');

                    return $qb
                        ->where($qb->expr()->orX(
                            $qb->expr()->eq('fs.project', ':project'),
                            $qb->expr()->isNull('fs.project')
                        ))
                        ->setParameter('project', $options['project'])
                    ;
                },
            ])
            ->add('file', FileType::class, [
                'required' => true,
                'data_class' => File::class,
                'constraints' => [
                    new FileConstaint(),
                    new NotBlank([
                        'message' => 'validation.constraints.media.file.not_blank',
                    ]),
                ],
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    $media = $event->getData();
                    $file = $media->getFile();
                    if ($file) {
                        $media->setUser($this->tokenStorage->getToken()->getUser());
                        $media->setPath($file->getClientOriginalName());
                        $media->setMimeType($file->getMimeType());
                        $media->setFileSize($file->getSize());
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
        $resolver
            ->setDefaults([
                'data_class' => Media::class,
                'project' => null,
            ])
            ->setAllowedTypes('project', Project::class)
        ;
    }
}
