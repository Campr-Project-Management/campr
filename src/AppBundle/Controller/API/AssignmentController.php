<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\Assignment\CreateType;
use AppBundle\Security\WorkPackageVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/assignment")
 */
class AssignmentController extends ApiController
{
    /**
     * All assignments for a specific WorkPackage.
     *
     * @Route("/{id}/list", name="app_api_assignment_list")
     * @Method({"GET"})
     *
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function listAction(WorkPackage $wp)
    {
        return $this->createApiResponse($wp->getAssignments());
    }

    /**
     * Retrieve Assignment information.
     *
     * @Route("/{id}", name="app_api_assignment_get")
     * @Method({"GET"})
     *
     * @param Assignment $assignment
     *
     * @return JsonResponse
     */
    public function getAction(Assignment $assignment)
    {
        $wp = $assignment->getWorkPackage();
        if (!$wp) {
            throw new \LogicException('Workpackage does not exist!');
        }
        $this->denyAccessUnlessGranted(WorkPackageVoter::VIEW, $wp);

        return $this->createApiResponse($assignment);
    }

    /**
     * Create a new Assignment.
     *
     * @Route("/create", name="app_api_assignment_create")
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

        return  $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Edit a specific Assignment.
     *
     * @Route("/{id}/edit", name="app_api_assignment_edit")
     * @Method({"PATCH"})
     *
     * @param Request    $request
     * @param Assignment $assignment
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Assignment $assignment)
    {
        $wp = $assignment->getWorkPackage();
        if (!$wp) {
            throw new \LogicException('Workpackage does not exist!');
        }
        $this->denyAccessUnlessGranted(WorkPackageVoter::EDIT, $wp);

        $form = $this->createForm(CreateType::class, $assignment, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            $em->flush();

            return  $this->createApiResponse($assignment, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Assignment.
     *
     * @Route("/{id}/delete", name="app_api_assignment_delete")
     * @Method({"DELETE"})
     *
     * @param Assignment $assignment
     *
     * @return JsonResponse
     */
    public function deleteAction(Assignment $assignment)
    {
        $wp = $assignment->getWorkPackage();
        if (!$wp) {
            throw new \LogicException('Workpackage does not exist!');
        }
        $this->denyAccessUnlessGranted(WorkPackageVoter::DELETE, $wp);

        $em = $this->getDoctrine()->getManager();
        $em->remove($assignment);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
