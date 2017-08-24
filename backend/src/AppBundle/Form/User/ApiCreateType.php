<?php

namespace AppBundle\Form\User;

use AppBundle\Entity\DistributionList;
use AppBundle\Entity\ProjectDepartment;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\Subteam;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ApiCreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.username',
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.first_name',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.last_name',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.email',
                    ]),
                    new Email([
                        'message' => 'invalid.email',
                    ]),
                ],
            ])
            ->add('company', TextType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('phone', TextType::class, [
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'required' => false,
            ])
            ->add('twitter', TextType::class, [
                'required' => false,
            ])
            ->add('instagram', TextType::class, [
                'required' => false,
            ])
            ->add('gplus', TextType::class, [
                'required' => false,
            ])
            ->add('linkedIn', TextType::class, [
                'required' => false,
            ])
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'download_link' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'invalid.image',
                    ]),
                ],
            ])
            ->add('subdomain', TextType::class, [
                'required' => false,
                'mapped' => false,
            ])
            ->add('distributionLists', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => DistributionList::class,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('subteams', EntityType::class, [
                'class' => Subteam::class,
                'multiple' => true,
            ])
            ->add('departments', EntityType::class, [
                'class' => ProjectDepartment::class,
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('roles', EntityType::class, [
                'class' => ProjectRole::class,
                'multiple' => true,
                'mapped' => false,
            ])
            ->add('showInResources', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('showInRaci', CheckboxType::class, [
                'mapped' => false,
            ])
            ->add('showInOrg', CheckboxType::class, [
                'mapped' => false,
            ])
        ;

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                if (isset($data['subdomain']) && !empty($data['subdomain'])) {
                    $form = $event->getForm();
                    $form->remove('distributionLists');
                    $form->remove('subteams');
                    $form->remove('departments');
                    $form->remove('showInResources');
                    $form->remove('showInRaci');
                    $form->remove('showInOrg');
                }
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) {
                $user = $event->getData();
                $user->setRoles([User::ROLE_USER]);
                $user->setPlainPassword(bin2hex(random_bytes(6)));
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true,
        ]);
    }
}
