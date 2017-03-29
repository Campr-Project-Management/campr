<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\RiskStrategy;
use AppBundle\Form\RiskStrategy\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/risk-strategies")
 */
class RiskStrategyController extends ApiController
{
    /**
     * Get all risk strategies.
     *
     * @Route(name="app_api_risk_strategies_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $riskStrategies = $this
            ->getDoctrine()
            ->getRepository(RiskStrategy::class)
            ->findAll()
        ;

        return $this->createApiResponse($riskStrategies);
    }

    /**
     * Create a new Risk Strategy.
     *
     * @Route(name="app_api_risk_strategies_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Risk Strategy by id.
     *
     * @Route("/{id}", name="app_api_risk_strategies_get")
     * @Method({"GET"})
     *
     * @param RiskStrategy $riskStrategy
     *
     * @return JsonResponse
     */
    public function getAction(RiskStrategy $riskStrategy)
    {
        return $this->createApiResponse($riskStrategy);
    }

    /**
     * Edit a specific Risk Strategy.
     *
     * @Route("/{id}", name="app_api_risk_strategies_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request      $request
     * @param RiskStrategy $riskStrategy
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, RiskStrategy $riskStrategy)
    {
        $form = $this->createForm(CreateType::class, $riskStrategy, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($riskStrategy);
            $em->flush();

            return $this->createApiResponse($riskStrategy, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Risk Strategy.
     *
     * @Route("/{id}", name="app_api_risk_strategies_delete")
     * @Method({"DELETE"})
     *
     * @param RiskStrategy $riskStrategy
     *
     * @return JsonResponse
     */
    public function deleteAction(RiskStrategy $riskStrategy)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskStrategy);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
