<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\EvaluationObjective;
use AppBundle\Form\EvaluationObjective\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/evaluation-objectives")
 */
class EvaluationObjectiveController extends ApiController
{
    const ENTITY_CLASS = EvaluationObjective::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Edit a specific evaluation objective.
     *
     * @Route("/{id}", name="app_api_evaluation_objectives_edit", options={"expose"=true}, requirements={"id": "\d+"})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request             $request
     * @param EvaluationObjective $evaluationObjective
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, EvaluationObjective $evaluationObjective)
    {
        $form = $this->getForm($evaluationObjective, ['method' => $request->getMethod(), 'csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($evaluationObjective);

            return $this->createApiResponse($evaluationObjective, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific EvaluationObjective.
     *
     * @Route("/{id}", name="app_api_evaluation_objectives_delete", options={"expose"=true}, requirements={"id": "\d+"})
     * @Method({"DELETE"})
     *
     * @param EvaluationObjective $evaluationObjective
     *
     * @return JsonResponse
     */
    public function deleteAction(EvaluationObjective $evaluationObjective)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($evaluationObjective);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Reorder evaluation objectives.
     *
     * @Route("/reorder", name="app_api_app_api_evaluation_objectives_reorder", options={"expose"=true})
     * @Method("PATCH")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function reorderAction(Request $request)
    {
        $this->getRepository()->updateSequences($request->request->all());

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
