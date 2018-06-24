<?php

namespace MainBundle\Form\User;

use MainBundle\Form\LocaleType;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class AccountType.
 */
class AccountType extends AbstractType
{
    /**
     * @var GoogleAuthenticator
     */
    private $googleAuthenticator;

    /**
     * AccountType constructor.
     *
     * @param GoogleAuthenticator $googleAuthenticator
     */
    public function __construct(GoogleAuthenticator $googleAuthenticator)
    {
        $this->googleAuthenticator = $googleAuthenticator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'disabled' => true,
            ])
            ->add('email', EmailType::class, [
                'disabled' => true,
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
            ->add('locale', LocaleType::class)
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
            ->add('medium', TextType::class, [
                'required' => false,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'match.password',
                'required' => true,
                'constraints' => [
                    new NotBlank(array(
                        'message' => 'not_blank.password',
                    )),
                    new Regex([
                        'pattern' => "/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
                        'message' => 'regex.password',
                    ]),
                ],
            ])
            ->add('twoFactor', CheckboxType::class, [
                'mapped' => false,
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();
                    $form = $event->getForm();

                    $plainPassword = isset($data['plainPassword'])
                        ? $data['plainPassword']
                        : []
                    ;
                    if (empty($plainPassword['first']) && empty($plainPassword['second'])) {
                        $form->remove('plainPassword');
                        unset($data['plainPassword']);
                        $event->setData($data);
                    }
                }
            )
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                function (FormEvent $event) {
                    $user = $event->getData();
                    $form = $event->getForm();
                    $twoFactor = $user->getGoogleAuthenticatorSecret() ? true : false;
                    $form->get('twoFactor')->setData($twoFactor);
                }
            )
            ->addEventListener(
                FormEvents::SUBMIT,
                function (FormEvent $event) {
                    $user = $event->getData();
                    $form = $event->getForm();
                    if ($form->get('twoFactor')->getData()) {
                        if ($user->getGoogleAuthenticatorSecret() === null) {
                            $user->setGoogleAuthenticatorSecret($this->googleAuthenticator->generateSecret());
                        }
                    } else {
                        $user->setGoogleAuthenticatorSecret(null);
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
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
