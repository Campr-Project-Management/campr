<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Label;
use AppBundle\Entity\Project;
use AppBundle\Form\Label\LabelType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/label")
 */
class LabelController extends ApiController
{
    /**
     * All labels for a specific project.
     *
     * @Route("/{id}/list", name="app_api_label_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction(Project $project)
    {
        return $this->createApiResponse($project->getLabels());
    }

    /**
     * Get Label by id.
     *
     * @Route("/{id}", name="app_api_label_get")
     * @Method({"GET"})
     *
     * @param Label $label
     *
     * @return JsonResponse
     */
    public function getAction(Label $label)
    {
        return $this->createApiResponse($label);
    }

    /**
     * Create a new label.
     *
     * @Route("/create", name="app_api_label_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(LabelType::class, null, ['csrf_protection' => false]);
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
     * Edit a specific label.
     *
     * @Route("/{id}/edit", name="app_api_label_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param Label   $label
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Label $label)
    {
        $project = $label->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(LabelType::class, $label, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($label);
            $em->flush();

            return $this->createApiResponse($label, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific label.
     *
     * @Route("/{id}/delete", name="app_api_label_delete")
     * @Method({"DELETE"})
     *
     * @param Label $label
     *
     * @return JsonResponse
     */
    public function deleteAction(Label $label)
    {
        $project = $label->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
