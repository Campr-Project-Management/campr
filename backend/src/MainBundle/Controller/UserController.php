<?php

namespace MainBundle\Controller;

use MainBundle\Form\User\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\User\LoginType;
use AppBundle\Entity\User;
use AppBundle\Form\User\ResetPasswordType;
use AppBundle\Form\User\ChangePasswordType;
use AppBundle\Form\User\RegisterType;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * User registration.
     *
     * @Route("/register", name="main_user_register")
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function registerAction(Request $request)
    {
        if (true) { // this is for PHPStorm to stop complaining
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag
                ->add(
                    'message',
                    $this
                        ->get('translator')
                        ->trans('registration.disabled', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_homepage');
        }

        if ($this->getUser()) {
            return $this->redirectToRoute('main_homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $formRegister = $this->createForm(RegisterType::class);
        $formRegister->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);
        $formReset = $this->createForm(ResetPasswordType::class);

        if ($formRegister->isSubmitted() && $formRegister->isValid()) {
            $user = $formRegister->getData();
            $user->setRoles($user->getRoles());
            $em->persist($user);
            $em->flush();

            $this
                ->get('app.service.mailer')
                ->sentRegistrationEmail($user)
            ;

            return $this->redirectToRoute('main_homepage');
        }

        return $this->render(
            'MainBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'register' => true,
                'error' => $error,
                'form' => $form->createView(),
                'form_register' => $formRegister->createView(),
                'form_reset' => $formReset->createView(),
                'route' => 'main_login',
            ]
        );
    }

    /**
     * Activate a specific user based on token.
     *
     * @Route("/activate/{token}", name="main_user_activate")
     *
     * @param string $token
     *
     * @return Response
     */
    public function activateAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->findOneByActivationToken($token)
        ;

        $flashBag = $this->get('session')->getFlashBag();
        if ($user) {
            $interval = $this->getParameter('activation_token_expiration');
            $today = new \DateTime();

            if ($today >= $user->getActivationTokenCreatedAt()
                && $today <= $user->getActivationTokenCreatedAt()->add(new \DateInterval($interval))
            ) {
                $user->setIsEnabled(true);
                $user->setActivationToken(null);
                $user->setActivationTokenCreatedAt(null);
                $user->setActivatedAt($today);
                $user->setUpdatedAt($today);
                $em->persist($user);
                $em->flush();

                $flashBag
                    ->add(
                        'message',
                        $this
                            ->get('translator')
                            ->trans('activation.activated', [], 'flashes')
                    )
                ;
            } else {
                $flashBag
                    ->add(
                        'message',
                        $this
                            ->get('translator')
                            ->trans('activation.expired', [], 'flashes')
                    )
                ;
                $flashBag
                    ->add(
                        'link',
                        $this
                            ->get('translator')
                            ->trans('activation.resend', [], 'flashes')
                    )
                ;
            }
        } else {
            $message = $this->get('translator')->trans('activation.not_found', [], 'flashes');
            throw $this->createNotFoundException($message);
        }

        return $this->render(
            'MainBundle:User:activation.html.twig',
            [
                'token' => $token,
            ]
        );
    }

    /**
     * Resend user activation token.
     *
     * @Route("/resend-activation/{token}", name="main_user_resend_activation")
     *
     * @param string $token
     *
     * @return RedirectResponse
     */
    public function resendActivationAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->findOneByActivationToken($token)
        ;

        if ($user) {
            $activationToken = $this->get('app.service.user')->generateActivationResetToken();
            $user->setActivationToken($activationToken);
            $user->setActivationTokenCreatedAt(new \DateTime());
            $em->persist($user);
            $em->flush();

            $this
                ->get('app.service.mailer')
                ->sentRegistrationEmail($user)
            ;
        } else {
            $message = $this->get('translator')->trans('activation.not_found', [], 'flashes');
            throw $this->createNotFoundException($message);
        }

        return $this->redirectToRoute('main_homepage');
    }

    /**
     * Request a reset password token.
     *
     * @Route("/request-reset", name="main_user_request_reset")
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function requestResetAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main_homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $formReset = $this->createForm(ResetPasswordType::class);
        $formReset->handleRequest($request);

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);
        $formRegister = $this->createForm(RegisterType::class);

        $userNotFound = false;
        if ($formReset->isSubmitted() && $formReset->isValid()) {
            $email = $formReset->getData()['email'];
            $user = $em
                ->getRepository(User::class)
                ->findOneByEmail($email)
            ;
            if ($user) {
                $resetToken = $this->get('app.service.user')->generateActivationResetToken();
                $user->setResetPasswordToken($resetToken);
                $user->setResetPasswordTokenCreatedAt(new \DateTime());
                $em->persist($user);
                $em->flush();

                $mailerService = $this->get('app.service.mailer');
                $mailerService->sendEmail(
                    'MainBundle:Email:user_reset_password.html.twig',
                    'info',
                    $user->getEmail(),
                    [
                        'token' => $resetToken,
                        'full_name' => $user->getFullName(),
                        'expiration_time' => $this->getParameter('reset_token_expiration_number'),
                    ]
                );

                return $this->redirectToRoute('main_homepage');
            }
            $userNotFound = true;
        }

        return $this->render(
            'MainBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'forgot_password' => true,
                'user_not_found' => $userNotFound,
                'error' => $error,
                'form' => $form->createView(),
                'form_register' => $formRegister->createView(),
                'form_reset' => $formReset->createView(),
                'route' => 'main_login',
            ]
        );
    }

    /**
     * Reset user password based on token.
     *
     * @Route("/reset-password/{token}", name="main_user_reset_password")
     *
     * @param Request $request
     * @param string  $token
     *
     * @return Response|RedirectResponse
     */
    public function resetPasswordAction(Request $request, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository(User::class)
            ->findOneByResetPasswordToken($token)
        ;

        if ($user) {
            $interval = $this->getParameter('reset_token_expiration');
            $today = new \DateTime();

            if ($today >= $user->getResetPasswordTokenCreatedAt()
                && $today <= $user->getResetPasswordTokenCreatedAt()->add(new \DateInterval($interval))
            ) {
                $form = $this->createForm(ChangePasswordType::class);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPlainPassword($form->getData()['plainPassword']);
                    $user->setResetPasswordToken(null);
                    $user->setResetPasswordTokenCreatedAt(null);
                    $user->setUpdatedAt($today);
                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('main_login');
                }

                return $this->render(
                    'MainBundle:User:reset.html.twig',
                    [
                        'form' => $form->createView(),
                        'token' => $token,
                    ]
                );
            } else {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->add(
                        'message',
                        $this
                            ->get('translator')
                            ->trans('reset.expired', [], 'flashes')
                    )
                ;
            }
        } else {
            $message = $this->get('translator')->trans('reset.not_found', [], 'flashes');
            throw $this->createNotFoundException($message);
        }

        return $this->render('MainBundle:User:reset.html.twig');
    }

    /**
     * Current user account settings.
     *
     * @Route("/account", name="main_user_account", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function accountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.account.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_user_account');
        }

        return $this->render(
            'MainBundle:User:account.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
                'qr_code' => $user->getGoogleAuthenticatorSecret()
                    ? $this->get('scheb_two_factor.security.google_authenticator')->getUrl($user)
                    : null,
            ]
        );
    }
}
