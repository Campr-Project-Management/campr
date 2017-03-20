<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Programme;
use AppBundle\Form\Programme\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/programmes")
 */
class ProgrammeController extends ApiController
{
    /**
     * Get all Programmes.
     *
     * @Route(name="app_api_programmes_list", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $programmes = $this
            ->getDoctrine()
            ->getRepository(Programme::class)
            ->findAll()
        ;

        return $this->createApiResponse($programmes);
    }

    /**
     * Create a new Programme.
     *
     * @Route(name="app_api_programmes_create")
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
     * Get Programme by id.
     *
     * @Route("/{id}", name="app_api_programmes_get")
     * @Method({"GET"})
     *
     * @param Programme $programme
     *
     * @return JsonResponse
     */
    public function getAction(Programme $programme)
    {
        return $this->createApiResponse($programme);
    }

    /**
     * Edit a specific Programme.
     *
     * @Route("/{id}", name="app_api_programmes_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request   $request
     * @param Programme $programme
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Programme $programme)
    {
        $form = $this->createForm(CreateType::class, $programme, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($programme);
            $em->flush();

            return $this->createApiResponse($programme, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Programme.
     *
     * @Route("/{id}", name="app_api_programmes_delete")
     * @Method({"DELETE"})
     *
     * @param Programme $programme
     *
     * @return JsonResponse
     */
    public function deleteAction(Programme $programme)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($programme);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
