<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Measure;
use AppBundle\Entity\Risk;
use AppBundle\Form\Risk\ApiType;
use AppBundle\Form\Measure\BaseType as MeasureBaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/risks")
 */
class RiskController extends ApiController
{
    const ENTITY_CLASS = Risk::class;
    const FORM_CLASS = ApiType::class;

    /**
     * Get Risk by id.
     *
     * @Route("/{id}", name="app_api_risks_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Risk $risk
     *
     * @return JsonResponse
     */
    public function getAction(Risk $risk)
    {
        return $this->createApiResponse($risk);
    }

    /**
     * Edit a specific Risk.
     *
     * @Route("/{id}", name="app_api_risks_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Risk    $risk
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Risk $risk)
    {
        $form = $this->getForm($risk, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($risk);

            return $this->createApiResponse($risk, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Risk.
     *
     * @Route("/{id}", name="app_api_risks_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Risk $risk
     *
     * @return JsonResponse
     */
    public function deleteAction(Risk $risk)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($risk);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create new Measure.
     *
     * @Route("/{id}/measures", name="app_api_risks_create_measure", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Risk    $risk
     *
     * @return JsonResponse
     */
    public function createMeasureAction(Request $request, Risk $risk)
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureBaseType::class, $measure, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $measure->setRisk($risk);
            $measure->setResponsibility($this->getUser());
            $this->persistAndFlush($measure);

            return $this->createApiResponse($measure, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
