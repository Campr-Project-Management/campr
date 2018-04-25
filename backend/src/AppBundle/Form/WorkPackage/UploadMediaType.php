<?php

namespace AppBundle\Form\WorkPackage;

use AppBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\File as FileConstaint;

class UploadMediaType extends AbstractType
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UploadMediaType constructor.
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
        $builder
            ->add('file', FileType::class, [
                'required' => true,
                'data_class' => File::class,
                'constraints' => [
                    new FileConstaint(),
                ],
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    /** @var Media $media */
                    $media = $event->getData();

                    /** @var UploadedFile $file */
                    $file = $media->getFile();
                    if (!$file) {
                        return;
                    }

                    $media->setUser($this->tokenStorage->getToken()->getUser());
                    $media->setOriginalName($file->getClientOriginalName());
                    $media->setPath($file->getClientOriginalName());
                    $media->setMimeType($file->getMimeType());
                    $media->setFileSize($file->getSize());
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
            ])
        ;
    }
}
