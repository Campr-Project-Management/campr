<?php

namespace AppBundle\Form\Project;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\File as FileConstaint;
use Symfony\Component\Validator\Constraints\NotBlank;

class ImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required' => true,
                'data_class' => File::class,
                'constraints' => [
                    new FileConstaint([
                        'mimeTypes' => [
                            'application/xml',
                        ],
                    ]),
                    new NotBlank([
                        'message' => 'not_blank.file',
                    ]),
                ],
            ])
        ;
    }
}
