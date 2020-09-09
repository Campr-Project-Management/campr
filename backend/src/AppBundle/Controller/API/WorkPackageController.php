<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\Cost;
use AppBundle\Entity\Media;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\Log;
use AppBundle\Entity\Comment;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Form\WorkPackage\ApiEditType;
use AppBundle\Paginator\SerializablePagerfanta;
use AppBundle\Repository\WorkPackageRepository;
use AppBundle\Security\WorkPackageVoter;
use AppBundle\Form\Assignment\BaseCreateType as AssignmentCreateType;
use AppBundle\Form\Comment\CreateType as CommentCreateType;
use Component\WorkPackage\WorkPackageEvents;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
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

        /** @var WorkPackageRepository $wpRepo */
        $wpRepo = $this->get('app.repository.work_package');

        if (isset($filters['page'])) {
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter(
                'front.per_page'
            );
            $result = $projects = $wpRepo->findUserFiltered($user, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $wpRepo->countTotalByUserAndFilters($user, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse(
            [
                'totalItems' => $wpRepo->countTotalByUserAndFilters($user),
                'items' => $wpRepo->findUserFiltered($user)->getQuery()->getResult(),
            ]
        );
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
     * Retrieve phase workpackages.
     *
     * @Route("/{id}/by-phase", name="app_api_phase_workpackages_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $phase
     * @param Request     $request
     *
     * @return JsonResponse
     */
    public function byPhaseAction(Request $request, WorkPackage $phase)
    {
        $filters = $request->query->all();
        $filters['phase'] = $phase;

        return $this->createApiResponse(
            $this
                ->getDoctrine()
                ->getRepository(WorkPackage::class)
                ->getQueryByProjectAndFilters($phase->getProject(), $filters)
                ->getResult()
        );
    }

    /**
     * Edit a specific WorkPackage.
     *
     * @Route("/{id}", name="app_api_workpackage_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT", "POST"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackage $wp)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::EDIT, $wp);

        $form = $this->createForm(
            ApiEditType::class,
            $wp,
            [
                'csrf_protection' => false,
                'method' => $request->getMethod(),
                'validation_groups' => ['Default', 'edit'],
            ]
        );

        $originalCosts = new ArrayCollection();
        foreach ($wp->getCosts() as $cost) {
            $originalCosts->add($cost);
        }

        $originalMedias = new ArrayCollection();
        foreach ($wp->getMedias() as $media) {
            $originalMedias->add($media);
        }

        $this->processForm($request, $form, !$request->isMethod('PATCH'));

        if (!$form->isValid()) {
            $errors = $this->getFormErrors($form);
            $errors = [
                'messages' => $errors,
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        /** @var WorkPackage $wp */
        $wp = $form->getData();
        $em = $this->getDoctrine()->getManager();

        foreach ($wp->getMedias() as $media) {
            $media->makeAsPermanent();
        }

        /** @var Cost $originalCost */
        foreach ($originalCosts as $originalCost) {
            if (!$wp->getCosts()->contains($originalCost)) {
                $em->remove($originalCost);
            }
        }

        /** @var Media $media */
        foreach ($originalMedias as $media) {
            if (!$wp->getMedias()->contains($media)) {
                $media->makeAsTemporary(0);
                $em->persist($media);
            }
        }

        $this->dispatchEvent(WorkPackageEvents::PRE_UPDATE, new WorkPackageEvent($wp));

        $this->get('app.repository.work_package')->add($wp);

        $this->dispatchEvent(WorkPackageEvents::POST_UPDATE, new WorkPackageEvent($wp));

        return $this->createApiResponse(
            $wp,
            $request->isMethod(Request::METHOD_POST)
                ? Response::HTTP_OK
                : Response::HTTP_ACCEPTED
        );
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

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($wp);
            $em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $errors = [
                'messages' => [
                    'dependency' => $this->get('translator')->trans(
                        'message.work_package_dependency_constraint',
                        [],
                        'messages'
                    ),
                ],
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

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

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Adds a new comment.
     *
     * @Route("/{id}/comments", name="app_api_workpackage_comments_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function commentsCreateAction(Request $request, WorkPackage $wp)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentCreateType::class, $comment, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);

            $wp->addComment($comment);
            $em->persist($wp);

            $log = new Log();
            $log->setObjId($wp->getId());
            $log->setClass(get_class($wp));
            $log->setOldValue(null);
            $log->setNewValue(
                [
                    'comment' => $comment->getBody(),
                ]
            );
            $log->setUser($this->getUser());

            $em->persist($log);
            $em->flush();

            return $this->createApiResponse($comment, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Returns all the changes made against a specific WorkPackage.
     *
     * @Route("/{id}/history", name="app_api_workpackage_history", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param WorkPackage $wp
     *
     * @return JsonResponse
     */
    public function historyAction(Request $request, WorkPackage $wp)
    {
        $pageSize = $request->query->get('pageSize', $this->getParameter('history.per_page'));
        $page = $request->query->get('page', 1);

        $paginator = $this
            ->get('app.repository.log')
            ->createWorkPackageHistoryPaginator($wp);

        $paginator->setMaxPerPage($pageSize);
        $paginator->setCurrentPage($page);

        $paginator = new SerializablePagerfanta($paginator);

        return $this->createApiResponse($paginator);
    }

    /**
     * Export WorkPackage as Xml.
     *
     * @Route("/{id}/export", name="app_api_workpackage_export", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return Response
     */
    public function exportAction(WorkPackage $workPackage)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::VIEW, $workPackage);

        $exportService = $this->get('app.service.export');
        $xmlTask = $exportService->exportTask($workPackage);

        return new Response($xmlTask->asXML());
    }
}
