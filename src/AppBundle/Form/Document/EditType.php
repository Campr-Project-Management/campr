<?php

namespace AppBundle\Form\Document;

use AppBundle\Entity\Document;
use AppBundle\Entity\Meeting;
use AppBundle\Entity\Project;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileConstaint;

class EditType extends AbstractType
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
            ])
            ->add('meetings', EntityType::class, [
                'class' => Meeting::class,
                'choice_label' => 'name',
                'translation_domain' => 'admin',
                'multiple' => true,
            ])
            ->add('file', FileType::class, [
                'required' => false,
                'data_class' => File::class,
                'constraints' => [
                    new FileConstaint(),
                ],
            ])
            ->addEventListener(
                FormEvents::SUBMIT,
                [$this, 'onSubmit']
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        $documentFile = $data->getFile();
        if ($documentFile) {
            $finder = new Finder();
            $finder->in($this->container->getParameter('documents_upload_folder'))->name($documentFile->getClientOriginalName());
            if (count($finder) > 0) {
                $error = $this->container->get('translator')->trans('admin.document.create.file_exists', [], 'admin');
                $form->get('file')->addError(new FormError($error));
            }
        }
    }
}
