<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Form\WorkPackageStatus\CreateType;
use AppBundle\Repository\WorkPackageStatusRepository;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/workpackage-statuses")
 */
class WorkPackageStatusController extends ApiController
{
    /**
     * All WorkPackageStatuses.
     *
     * @Route(name="app_api_workpackage_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        /** @var WorkPackageStatusRepository $repository */
        $repository = $this->get('app.repository.work_package_status');

        return $this->createApiResponse($repository->findAll());
    }

    /**
     * Create a new WorkPackageStatus.
     *
     * @Route(name="app_api_workpackage_statuses_create")
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

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get WorkPackageStatuses by id.
     *
     * @Route("/{id}", name="app_api_workpackage_statuses_get")
     * @Method({"GET"})
     *
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return JsonResponse
     */
    public function getAction(WorkPackageStatus $workPackageStatus)
    {
        return $this->createApiResponse($workPackageStatus);
    }

    /**
     * Edit a specific WorkPackageStatus.
     *
     * @Route("/{id}", name="app_api_workpackage_statuses_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request           $request
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, WorkPackageStatus $workPackageStatus)
    {
        $form = $this->createForm(CreateType::class, $workPackageStatus, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($workPackageStatus);
            $em->flush();

            return $this->createApiResponse($workPackageStatus, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific WorkPackageStatus.
     *
     * @Route("/{id}", name="app_api_workpackage_statuses_delete")
     * @Method({"DELETE"})
     *
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return JsonResponse
     */
    public function deleteAction(WorkPackageStatus $workPackageStatus)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($workPackageStatus);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
