<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Status;
use AppBundle\Form\Status\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/statuses")
 */
class StatusController extends ApiController
{
    /**
     * Get all status.
     *
     * @Route(name="app_api_statuses_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $status = $this
            ->getDoctrine()
            ->getRepository(Status::class)
            ->findAll()
        ;

        return $this->createApiResponse($status);
    }

    /**
     * Create a new Status.
     *
     * @Route(name="app_api_statuses_create", options={"expose"=true})
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
     * Get Status by id.
     *
     * @Route("/{id}", name="app_api_statuses_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Status $status
     *
     * @return JsonResponse
     */
    public function getAction(Status $status)
    {
        return $this->createApiResponse($status);
    }

    /**
     * Edit a specific Status.
     *
     * @Route("/{id}", name="app_api_statuses_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Status  $status
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Status $status)
    {
        $form = $this->createForm(CreateType::class, $status, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            return $this->createApiResponse($status, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Status.
     *
     * @Route("/{id}", name="app_api_statuses_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Status $status
     *
     * @return JsonResponse
     */
    public function deleteAction(Status $status)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($status);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
