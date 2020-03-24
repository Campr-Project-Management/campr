<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Label;
use AppBundle\Form\Label\LabelType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/labels")
 */
class LabelController extends ApiController
{
    const ENTITY_CLASS = Label::class;

    /**
     * All labels.
     *
     * @Route("", name="app_api_labels_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function labelsAction()
    {
        return $this->createApiResponse(
            $this
                ->getRepository()
                ->findAll()
        );
    }

    /**
     * Create a new Label.
     *
     * @Route("", name="app_api_label_create", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createLabelAction(Request $request)
    {
        $label = new Label();

        $form = $this->createForm(LabelType::class, $label, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $this->persistAndFlush($label);

            return $this->createApiResponse($label, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Get Label by id.
     *
     * @Route("/{id}", name="app_api_label_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Label $label
     *
     * @return JsonResponse
     */
    public function getAction(Label $label)
    {
        return $this->createApiResponse($label);
    }

    /**
     * Edit a specific label.
     *
     * @Route("/{id}", name="app_api_label_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Label   $label
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Label $label)
    {
        $form = $this->createForm(LabelType::class, $label, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($label);
            $em->flush();

            return $this->createApiResponse($label, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific label.
     *
     * @Route("/{id}", name="app_api_label_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Label $label
     *
     * @return JsonResponse
     */
    public function deleteAction(Label $label)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
