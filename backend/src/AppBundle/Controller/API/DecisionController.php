<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Decision;
use AppBundle\Form\Decision\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/decisions")
 */
class DecisionController extends ApiController
{
    const ENTITY_CLASS = Decision::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Retrieve decision information.
     *
     * @Route("/{id}", name="app_api_decisions_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function getAction(Decision $decision)
    {
        return $this->createApiResponse($decision);
    }

    /**
     * Edit a specific Decision.
     *
     * @Route("/{id}", name="app_api_decisions_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request  $request
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Decision $decision)
    {
        $form = $this->getForm($decision, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($decision);

            return $this->createApiResponse($decision, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Decision.
     *
     * @Route("/{id}", name="app_api_decisions_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function deleteAction(Decision $decision)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($decision);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
