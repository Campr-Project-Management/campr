<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Measure;
use AppBundle\Entity\MeasureComment;
use AppBundle\Form\Measure\BaseType;
use AppBundle\Form\MeasureComment\BaseType as MeasureCommentBaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/measures")
 */
class MeasureController extends ApiController
{
    const ENTITY_CLASS = Measure::class;
    const FORM_CLASS = BaseType::class;

    /**
     * Get Measure by id.
     *
     * @Route("/{id}", name="app_api_measures_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Measure $measure
     *
     * @return JsonResponse
     */
    public function getAction(Measure $measure)
    {
        return $this->createApiResponse($measure);
    }

    /**
     * Edit a specific Measure.
     *
     * @Route("/{id}", name="app_api_measures_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Measure $measure
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Measure $measure)
    {
        $form = $this->getForm($measure, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($measure);

            return $this->createApiResponse($measure, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Measure.
     *
     * @Route("/{id}", name="app_api_measures_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Measure $measure
     *
     * @return JsonResponse
     */
    public function deleteAction(Measure $measure)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($measure);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create new measure comment.
     *
     * @Route("/{id}/comments", name="app_api_measures_create_comment", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Measure $measure
     *
     * @return JsonResponse
     */
    public function createCommentAction(Request $request, Measure $measure)
    {
        $comment = new MeasureComment();
        $form = $this->createForm(MeasureCommentBaseType::class, $comment, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $comment->setMeasure($measure);
            $comment->setResponsibility($this->getUser());
            $this->persistAndFlush($comment);

            return $this->createApiResponse($comment, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
