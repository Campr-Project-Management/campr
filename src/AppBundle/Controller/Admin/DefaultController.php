<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/index", name="app_admin_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:Admin/Default:layout.html.twig');
    }
}
