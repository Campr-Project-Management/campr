<?php

namespace MainBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\User\LoginType;
use AppBundle\Form\User\RegisterType;
use AppBundle\Form\User\ResetPasswordType;
use MainBundle\Form\ContactType;
use MainBundle\Form\User\HomepageRegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_homepage", options={"expose"=true})
     */
    public function indexAction()
    {
        $user = new User();
        $signUpForm = $this->createForm(HomepageRegisterType::class, $user, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('main_register'),
        ]);

        return $this->render('MainBundle:Default:index.html.twig', [
            'signUpForm' => $signUpForm->createView(),
        ]);
    }

    /**
     * @Route("/register", name="main_register", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function registerAction(Request $request)
    {
        if ($request->isMethod(Request::METHOD_GET)) {
            return $this->redirectToRoute('main_homepage');
        }

        $user = new User();
        $signUpForm = $this->createForm(HomepageRegisterType::class, $user, [
            'method' => Request::METHOD_POST,
            'action' => $this->generateUrl('main_register'),
        ]);

        $signUpForm->handleRequest($request);
        if ($request->isMethod(Request::METHOD_POST) && $signUpForm->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $mailer = $this->get('app.service.mailer');

            $user->setPlainPassword(substr(md5(microtime()), rand(0, 26), 6));

            $em->persist($user);
            $em->flush();

            $mailer
                ->sendEmail(
                    'MainBundle:Email:user_register.html.twig',
                    'info',
                    $user->getEmail(),
                    [
                        'token' => $user->getActivationToken(),
                        'full_name' => $user->getFullName(),
                        'plain_password' => $user->getPlainPassword(),
                        'expiration_time' => $this->getParameter('activation_token_expiration_number'),
                    ]
                )
            ;

            $this->addFlash('registration_success', 'flash.registration_success');

            return $this->redirectToRoute('main_homepage');
        }

        return $this->render('MainBundle:Default:index.html.twig', [
            'signUpForm' => $signUpForm->createView(),
        ]);
    }

    /**
     * Contact page.
     *
     * @Route("/contact", name="main_contact")
     *
     * @return Response|RedirectResponse
     */
    public function contactAction(Request $request)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mailerService = $this->get('app.service.mailer');
            $mailerService->addFromParameter('contact_from', ['email' => $data['email'], 'name' => $data['full_name']]);
            $mailerService
                ->sendEmail(
                    'MainBundle:Email:contact.html.twig',
                    'contact_from',
                    'info@campr.biz',
                    ['subject' => $data['subject'], 'fullName' => $data['full_name'], 'message' => $data['message']]
                )
            ;

            $this
                ->get('session')
                ->getFlashBag()
                ->add(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.contact.message.sent', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_contact');
        }

        return $this->render(
            'MainBundle:Default:contact.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Login into application page.
     *
     * @Route("/login", name="main_login")
     *
     * @return Response|RedirectResponse
     */
    public function loginAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('main_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);
        $formRegister = $this->createForm(RegisterType::class);
        $formReset = $this->createForm(ResetPasswordType::class);

        return $this->render(
            'MainBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'login' => true,
                'error' => $error,
                'form' => $form->createView(),
                'form_register' => $formRegister->createView(),
                'form_reset' => $formReset->createView(),
                'route' => 'main_login',
            ]
        );
    }

    /**
     * Logout from application.
     *
     * @Route("/logout", name="main_logout")
     */
    public function logoutAction()
    {
    }
}
