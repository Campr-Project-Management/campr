<?php

namespace AppBundle\Controller\API;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin/api")
 */
class DefaultController extends Controller
{
    /**
     * @Route("login", name="app_api_login")
     */
    public function loginAction()
    {
    }
}
