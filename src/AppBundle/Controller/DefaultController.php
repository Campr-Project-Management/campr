<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\User\LoginType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
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

        return $this->render(
            'AppBundle:Default:login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
                'form' => $form->createView(),
                'route' => 'app_login',
            ]
        );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logoutAction()
    {
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
    }
}
