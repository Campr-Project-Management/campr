<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\WorkPackage;
use AppBundle\Form\WorkPackage\CreateType;
use AppBundle\Security\WorkPackageVoter;
use AppBundle\Form\Assignment\BaseCreateType as AssignmentCreateType;
use Doctrine\ORM\Tools\Pagination\Paginator;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/workpackages")
 */
class WorkPackageController extends ApiController
{
    /**
     * All tasks for the current user.
     *
     * @Route(name="app_api_workpackage_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $filters = $request->query->all();
        $user = $this->getUser();
        $wpQuery = $this
            ->getDoctrine()
            ->getRepository(WorkPackage::class)
            ->findUserFiltered($user, $filters)
        ;

        $pageSize = $this->getParameter('front.per_page');
        $currentPage = isset($filters['page']) ? $filters['page'] : 1;
        $paginator = new Paginator($wpQuery);
        $paginator->getQuery()
            ->setFirstResult($pageSize * ($currentPage - 1))
            ->setMaxResults($pageSize)
        ;

        $responseArray['totalItems'] = count($paginator);
        $responseArray['items'] = $paginator->getIterator()->getArrayCopy();

        return $this->createApiResponse($responseArray);
    }

    /**
     * Retrieve WorkPackage information.
     *
     * @Route("/{id}", name="app_api_workpackage_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return JsonResponse
     */
    public function getAction(WorkPackage $workPackage)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::VIEW, $workPackage);

        return $this->createApiResponse($workPackage);
    }

    /**
     * Edit a specific WorkPackage.
     *
     * @Route("/{id}", name="app_api_workpackage_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackage $wp)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::EDIT, $wp);

        $form = $this->createForm(CreateType::class, $wp, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($wp);
            $em->flush();

            return $this->createApiResponse($wp, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific WorkPackage.
     *
     * @Route("/{id}", name="app_api_workpackage_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function deleteAction(WorkPackage $wp)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::DELETE, $wp);

        $em = $this->getDoctrine()->getManager();
        $em->remove($wp);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * All aassignments for a specific WorkPackage.
     *
     * @Route("/{id}/assignments", name="app_api_workpackage_assignments", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function assignmentsAction(WorkPackage $wp)
    {
        return $this->createApiResponse($wp->getAssignments());
    }

    /**
     * Create a new Assignment.
     *
     * @Route("/{id}/assignments", name="app_api_workpackage_assignments_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function assignmentsCreateAction(Request $request, WorkPackage $wp)
    {
        $assignment = new Assignment();
        $assignment->setWorkPackage($wp);

        $form = $this->createForm(AssignmentCreateType::class, $assignment, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            $em->flush();

            return $this->createApiResponse($assignment, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return  $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
