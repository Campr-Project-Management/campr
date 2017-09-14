<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Lesson;
use AppBundle\Form\Lesson\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/lessons")
 */
class LessonController extends ApiController
{
    const ENTITY_CLASS = Lesson::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Edit a specific lesson.
     *
     * @Route("/{id}", name="app_api_lessons_edit", options={"expose"=true}, requirements={"id": "\d+"})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Lesson  $lesson
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Lesson $lesson)
    {
        $form = $this->getForm($lesson, ['method' => $request->getMethod(), 'csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($lesson);

            return $this->createApiResponse($lesson, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Lesson.
     *
     * @Route("/{id}", name="app_api_lessons_delete", options={"expose"=true}, requirements={"id": "\d+"})
     * @Method({"DELETE"})
     *
     * @param Lesson $lesson
     *
     * @return JsonResponse
     */
    public function deleteAction(Lesson $lesson)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($lesson);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Reorder lessons.
     *
     * @Route("/reorder", name="app_api_app_api_lessons_reorder", options={"expose"=true})
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
