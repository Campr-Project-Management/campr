<?php

namespace AppBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api/users")
 */
class UserController extends Controller
{
    /**
     * @Route("", name="app_api_default")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function defaultAction()
    {
        return new JsonResponse([], 200);
    }
}
