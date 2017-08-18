<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\OpportunityStrategy;
use AppBundle\Form\OpportunityStrategy\BaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/opportunity-strategies")
 */
class OpportunityStrategyController extends ApiController
{
    const ENTITY_CLASS = OpportunityStrategy::class;
    const FORM_CLASS = BaseType::class;

    /**
     * Create a new OpportunityStrategy.
     *
     * @Route(name="app_api_opportunity_strategies_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm(null, ['method' => $request->getMethod()]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $this->persistAndFlush($form->getData());

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Opportunity Strategy by id.
     *
     * @Route("/{id}", name="app_api_opportunity_strategies_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return JsonResponse
     */
    public function getAction(OpportunityStrategy $opportunityStrategy)
    {
        return $this->createApiResponse($opportunityStrategy);
    }

    /**
     * Edit a specific Opportunity Strategy.
     *
     * @Route("/{id}", name="app_api_opportunity_strategies_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request             $request
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, OpportunityStrategy $opportunityStrategy)
    {
        $form = $this->getForm($opportunityStrategy, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($opportunityStrategy);

            return $this->createApiResponse($opportunityStrategy, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Opportunity Strategy.
     *
     * @Route("/{id}", name="app_api_opportunity_strategies_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return JsonResponse
     */
    public function deleteAction(OpportunityStrategy $opportunityStrategy)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($opportunityStrategy);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
