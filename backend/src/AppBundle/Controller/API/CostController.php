<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Cost;
use AppBundle\Form\Cost\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/costs")
 */
class CostController extends ApiController
{
    const ENTITY_CLASS = Cost::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_api_cost_list", options={"expose"=true})
     * @Method({"GET"})
     */
    public function listAction()
    {
        $out = $this->getRepository()->findAll();

        return $this->createApiResponse($out);
    }

    /**
     * @Route("", name="app_api_cost_create", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm(null, ['method' => $request->getMethod()]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $cost = $form->getData();
            $this->persistAndFlush($cost);

            return $this->createApiResponse($cost, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_cost_get", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getAction(Cost $cost)
    {
        return $this->createApiResponse($cost);
    }

    /**
     * @Route("/{id}", name="app_api_cost_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(Request $request, Cost $cost)
    {
        $form = $this->getForm($cost, ['method' => $request->getMethod()]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($cost);

            return $this->createApiResponse($cost, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
            'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_cost_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Cost $cost)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($cost);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
