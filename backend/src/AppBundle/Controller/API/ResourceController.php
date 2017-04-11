<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Resource;
use AppBundle\Form\Resource\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/resources")
 */
class ResourceController extends ApiController
{
    const ENTITY_CLASS = Resource::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_api_resource_list", options={"expose"=true})
     * @Method({"GET"})
     */
    public function listAction()
    {
        $out = $this->getRepository()->findAll();

        return $this->createApiResponse($out);
    }

    /**
     * @Route("", name="app_api_resource_create", options={"expose"=true})
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $form = $this->getForm(null, ['method' => $request->getMethod()]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $resource = $form->getData();
            $this->persistAndFlush($resource);

            return $this->createApiResponse($resource, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse([
            'messages' => $this->getFormErrors($form),
        ]);
    }

    /**
     * @Route("/{id}", name="app_api_resource_get", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getAction(Resource $resource)
    {
        return $this->createApiResponse($resource);
    }

    /**
     * @Route("/{id}", name="app_api_resource_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(Request $request, Resource $resource)
    {
        $form = $this->getForm($resource, ['method' => $request->getMethod()]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($resource);

            return $this->createApiResponse($resource, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse([
            'messages' => $this->getFormErrors($form),
        ]);
    }

    /**
     * @Route("/{id}", name="app_api_resource_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Resource $resource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($resource);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
