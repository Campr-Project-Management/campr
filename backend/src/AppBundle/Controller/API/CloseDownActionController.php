<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\CloseDownAction;
use AppBundle\Form\CloseDownAction\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/close-down-actions")
 */
class CloseDownActionController extends ApiController
{
    const ENTITY_CLASS = CloseDownAction::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Get CloseDownAction by id.
     *
     * @Route("/{id}", name="app_api_close_down_actions_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param CloseDownAction $closeDownAction
     *
     * @return JsonResponse
     */
    public function getAction(CloseDownAction $closeDownAction)
    {
        return $this->createApiResponse($closeDownAction);
    }

    /**
     * Edit a specific close down action.
     *
     * @Route("/{id}", name="app_api_close_down_actions_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request         $request
     * @param CloseDownAction $closeDownAction
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, CloseDownAction $closeDownAction)
    {
        $form = $this->getForm($closeDownAction, ['method' => $request->getMethod(), 'csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($closeDownAction);

            return $this->createApiResponse($closeDownAction, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific close down action.
     *
     * @Route("/{id}", name="app_api_close_down_actions_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param CloseDownAction $closeDownAction
     *
     * @return JsonResponse
     */
    public function deleteAction(CloseDownAction $closeDownAction)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($closeDownAction);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
