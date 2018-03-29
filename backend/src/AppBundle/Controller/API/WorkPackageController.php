<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Assignment;
use AppBundle\Entity\Cost;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\WorkPackage;
use AppBundle\Entity\Log;
use AppBundle\Entity\Comment;
use AppBundle\Event\WorkPackageEvent;
use AppBundle\Form\WorkPackage\ApiCreateType;
use AppBundle\Form\WorkPackage\MilestoneType;
use AppBundle\Form\WorkPackage\PhaseType;
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
use Gaufrette\Exception\FileAlreadyExists;

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
            $filters['pageSize'] = isset($filters['pageSize']) ? $filters['pageSize'] : $this->getParameter('front.per_page');
            $result = $projects = $wpRepo->findUserFiltered($user, $filters)->getQuery()->getResult();
            $responseArray['totalItems'] = $wpRepo->countTotalByUserAndFilters($user, $filters);
            $responseArray['pageSize'] = $filters['pageSize'];
            $responseArray['items'] = $result;

            return $this->createApiResponse($responseArray);
        }

        return $this->createApiResponse([
            'totalItems' => $wpRepo->countTotalByUserAndFilters($user),
            'items' => $wpRepo->findUserFiltered($user)->getQuery()->getResult(),
        ]);
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
            ApiCreateType::class,
            $wp,
            ['csrf_protection' => false, 'method' => $request->getMethod()]
        );

        $originalCosts = new ArrayCollection();
        foreach ($wp->getCosts() as $cost) {
            $originalCosts->add($cost);
        }

        $this->processForm(
            $request,
            $form,
            in_array($request->getMethod(), [Request::METHOD_PUT, Request::METHOD_POST])
        );

        // @TODO: Make filesystem selection dynamic
        $em = $this->getDoctrine()->getManager();
        $fileSystem = $wp
            ->getProject()
            ->getFileSystems()
            ->filter(function (FileSystem $fs) {
                return FileSystem::LOCAL_ADAPTER === $fs->getDriver();
            })
            ->first()
        ;

        if (!$fileSystem) {
            $fileSystem = $em
                ->getRepository(FileSystem::class)
                ->findOneBy([
                    'driver' => FileSystem::LOCAL_ADAPTER,
                ])
            ;
            if (!$fileSystem) {
                return $this->createApiResponse(
                    [
                        'messages' => [
                            'Filesystem is missing. Please contact us.',
                        ],
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }

        try {
            if ($form->isValid()) {
                /** @var WorkPackage $wp */
                $wp = $form->getData();

                /** @var Cost $originalCost */
                foreach ($originalCosts as $originalCost) {
                    if (!$wp->getCosts()->contains($originalCost)) {
                        $em->remove($originalCost);
                    }
                }

                foreach ($wp->getMedias() as $media) {
                    $media->setFileSystem($fileSystem);
                }

                $this->dispatchEvent(WorkPackageEvents::PRE_UPDATE, new WorkPackageEvent($wp));

                $em->persist($wp);
                $em->flush();

                $this->dispatchEvent(WorkPackageEvents::POST_UPDATE, new WorkPackageEvent($wp));

                return $this->createApiResponse(
                    $wp,
                    $request->isMethod(Request::METHOD_POST)
                        ? Response::HTTP_OK
                        : Response::HTTP_ACCEPTED
                );
            }
        } catch (FileAlreadyExists $ex) {
            return $this->createApiResponse(
                ['messages' => ['medias' => [$ex->getMessage()]]],
                Response::HTTP_BAD_REQUEST
            );
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Edit a specific WorkPackage.
     *
     * @Route("/phase/{id}", name="app_api_workpackage_phase_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request     $request
     * @param WorkPackage $phase
     *
     * @return JsonResponse
     */
    public function editPhaseAction(Request $request, WorkPackage $phase)
    {
        $form = $this->createForm(PhaseType::class, $phase, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($phase);

            return $this->createApiResponse($phase, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Edit a specific WorkPackage.
     *
     * @Route("/milestone/{id}", name="app_api_workpackage_milestone_edit", options={"expose"=true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request     $request
     * @param WorkPackage $milestone
     *
     * @return JsonResponse
     */
    public function editMilestoneAction(Request $request, WorkPackage $milestone)
    {
        $form = $this->createForm(MilestoneType::class, $milestone, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($milestone);

            return $this->createApiResponse($milestone, Response::HTTP_ACCEPTED);
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

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($wp);
            $em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            $errors = [
                'messages' => [
                    'dependency' => $this->get('translator')->trans('message.work_package_dependency_constraint', [], 'messages'),
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

        return  $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
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
            $log->setNewValue([
                'comment' => $comment->getBody(),
            ]);
            $log->setUser($this->getUser());

            $em->persist($log);
            $em->flush();

            return $this->createApiResponse($comment, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return  $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
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
        $filters = $request->query->all();
        $filters['pageSize'] = (isset($filters['pageSize']))
            ? $filters['pageSize']
            : $this->getParameter('front.per_page')
        ;
        $filters['page'] = isset($filters['page']) ? intval($filters['page']) : 1;

        $em = $this->getDoctrine()->getManager();

        $history = $em
            ->getRepository(Log::class)
            ->findByObjectAndFilters(
                WorkPackage::class,
                $wp->getId(),
                $filters
            )
        ;

        return $this->createApiResponse($history);
    }

    /**
     * Export WorkPackage as Xml.
     *
     * @Route("/{id}/export", name="app_api_workpackage_export", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param WorkPackage $workPackage
     *
     * @return xml file
     */
    public function exportAction(WorkPackage $workPackage)
    {
        $this->denyAccessUnlessGranted(WorkPackageVoter::VIEW, $workPackage);

        $exportService = $this->get('app.service.export');
        $xmlTask = $exportService->exportTask($workPackage);

        return new Response($xmlTask->asXML());
    }
}
