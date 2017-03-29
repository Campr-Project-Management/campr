<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * API user login.
     *
     * @Route("/api/login", name="app_api_login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
    }
}
