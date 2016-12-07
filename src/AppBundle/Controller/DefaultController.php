<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\User\LoginType;
use AppBundle\Form\User\RegisterType;
use AppBundle\Form\User\ResetPasswordType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render(
            'AppBundle:Default:index.html.twig'
        );
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginType::class);
        $formRegister = $this->createForm(RegisterType::class);
        $formReset = $this->createForm(ResetPasswordType::class);

        return $this->render(
            'AppBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'login' => true,
                'error' => $error,
                'form' => $form->createView(),
                'form_register' => $formRegister->createView(),
                'form_reset' => $formReset->createView(),
                'route' => 'app_login',
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logoutAction()
    {
    }
}
