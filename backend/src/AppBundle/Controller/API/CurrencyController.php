<?php

namespace AppBundle\Controller\API;

use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/currencies")
 */
class CurrencyController extends ApiController
{
    /**
     * @Route("/", name="app_api_currencies", options={"expose"=true})
     * @Method({"GET"})
     */
    public function indexAction(): JsonResponse
    {
        $currencies = $this->get('app.repository.currency')->findAll();

        return $this->createApiResponse($currencies);
    }
}
