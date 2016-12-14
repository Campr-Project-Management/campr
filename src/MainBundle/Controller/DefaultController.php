<?php

namespace MainBundle\Controller;

use AppBundle\Form\User\LoginType;
use AppBundle\Form\User\RegisterType;
use AppBundle\Form\User\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main_homepage")
     */
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
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
