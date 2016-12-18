<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default application controller.
 */
class DefaultController extends Controller
{
    /**
     * Application homepage.
     *
     * @Route("/", name="app_homepage", options={"expose"=true})
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render(
            'AppBundle:Default:index.html.twig'
        );
    }
}
