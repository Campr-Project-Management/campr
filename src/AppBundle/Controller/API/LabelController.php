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
        $data = $request->request->all();
        $form = $this->createForm(LabelType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), Response::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
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
        if ($project = $label->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $data = $request->request->all();
        $form = $this->createForm(LabelType::class, $label, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($label);
            $em->flush();

            return $this->createApiResponse($label);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific label.
     *
     * @Route("/{id}/delete", name="app_api_label_delete")
     * @Method({"GET"})
     *
     * @param Label $label
     *
     * @return JsonResponse
     */
    public function deleteAction(Label $label)
    {
        if ($project = $label->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
