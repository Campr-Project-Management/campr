<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\Assignment\CreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/assignment")
 */
class AssignmentController extends Controller
{
    /**
     * All aassignments for a specific WorkPackage.
     *
     * @Route("/{id}/list", name="app_api_assignment_list")
     * @Method({"GET", "POST"})
     *
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function listAction(WorkPackage $wp)
    {
        $assignments = $this
            ->getDoctrine()
            ->getRepository(Assignment::class)
            ->findByWorkPackage($wp)
        ;

        $assign = [];
        foreach ($assignments as $assignment) {
            $assign[] = $this->serialize($assignment);
        }

        return new JsonResponse($assign);
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
        return new JsonResponse($this->serialize($assignment));
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
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return new JsonResponse($this->serialize($form->getData()));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
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
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(CreateType::class, $assignment, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            $em->flush();

            return new JsonResponse($this->serialize($assignment));
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'errors' => $errors,
        ]);
    }

    /**
     * Delete a specific Assignment.
     *
     * @Route("/{id}/delete", name="app_api_assignment_delete")
     * @Method({"GET"})
     *
     * @param Assignment $assignment
     *
     * @return JsonResponse
     */
    public function deleteAction(Assignment $assignment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($assignment);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create array with needed information from Assignment object.
     *
     * @param Assignment $assignment
     *
     * @return array
     */
    private function serialize(Assignment $assignment)
    {
        $info = [
            'id' => $assignment->getId(),
            'work_package' => $assignment->getWorkPackage() ? $assignment->getWorkPackage()->getId() : null,
            'work_package_name' => $assignment->getWorkPackage() ? $assignment->getWorkPackage()->getName() : null,
            'percent_complete' => $assignment->getPercentWorkComplete(),
            'milestone' => $assignment->getMilestone(),
            'confirmed' => $assignment->getConfirmed(),
            'started_at' => $assignment->getStartedAt() ? $assignment->getStartedAt()->format('Y-m-d H:i:s') : null,
            'finished_at' => $assignment->getFinishedAt() ? $assignment->getFinishedAt()->format('Y-m-d H:i:s') : null,
        ];

        $info['timephases'] = [];
        if (!$assignment->getTimephases()->isEmpty()) {
            foreach ($assignment->getTimephases() as $tp) {
                $info['timephases'][] = [
                    'id' => $tp->getId(),
                    'type' => $tp->getType(),
                    'unit' => $tp->getUnit(),
                    'value' => $tp->getValue(),
                    'started_at' => $tp->getStartedAt() ? $tp->getStartedAt()->format('Y-m-d H:i:s') : null,
                    'finished_at' => $tp->getFinishedAt() ? $tp->getFinishedAt()->format('Y-m-d H:i:s') : null,
                ];
            }
        }

        return $info;
    }
}
