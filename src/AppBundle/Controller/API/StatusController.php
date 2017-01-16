<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Status;
use AppBundle\Form\Status\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/status")
 */
class StatusController extends ApiController
{
    /**
     * Get all status.
     *
     * @Route("/list", name="app_api_status_list")
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
     * @Route("/create", name="app_api_status_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, null, ['csrf_protection' => false]);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->createApiResponse($form->getData(), JsonResponse::HTTP_CREATED);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Get Status by id.
     *
     * @Route("/{id}", name="app_api_status_get")
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
     * @Route("/{id}/edit", name="app_api_status_edit")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Status  $status
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Status $status)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $status, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($status);
            $em->flush();

            return $this->createApiResponse($status);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Status.
     *
     * @Route("/{id}/delete", name="app_api_status_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
