<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Unit;
use AppBundle\Form\Unit\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/units")
 */
class UnitController extends ApiController
{
    const ENTITY_CLASS = Unit::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("/{id}", name="app_api_unit_get", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getAction(Unit $unit)
    {
        return $this->createApiResponse($unit);
    }

    /**
     * @Route("/{id}", name="app_api_unit_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(Request $request, Unit $unit)
    {
        $form = $this->getForm($unit, ['method' => $request->getMethod()]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($unit);

            return $this->createApiResponse($unit, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse([
            'messages' => $this->getFormErrors($form),
        ]);
    }

    /**
     * @Route("/{id}", name="app_api_unit_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Unit $unit)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($unit);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
