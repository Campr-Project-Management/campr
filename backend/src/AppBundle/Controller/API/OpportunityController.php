<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Measure;
use AppBundle\Entity\Opportunity;
use AppBundle\Form\Opportunity\BaseType;
use AppBundle\Form\Measure\BaseType as MeasureBaseType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/opportunities")
 */
class OpportunityController extends ApiController
{
    const ENTITY_CLASS = Opportunity::class;
    const FORM_CLASS = BaseType::class;

    /**
     * Get Opportunity by id.
     *
     * @Route("/{id}", name="app_api_opportunities_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Opportunity $opportunity
     *
     * @return JsonResponse
     */
    public function getAction(Opportunity $opportunity)
    {
        return $this->createApiResponse($opportunity);
    }

    /**
     * Edit a specific Opportunity.
     *
     * @Route("/{id}", name="app_api_opportunities_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request     $request
     * @param Opportunity $opportunity
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Opportunity $opportunity)
    {
        $form = $this->getForm($opportunity, ['method' => $request->getMethod()]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($opportunity);

            return $this->createApiResponse($opportunity, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific opportunity.
     *
     * @Route("/{id}", name="app_api_opportunities_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Opportunity $opportunity
     *
     * @return JsonResponse
     */
    public function deleteAction(Opportunity $opportunity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($opportunity);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Create new Measure.
     *
     * @Route("/{id}/measures", name="app_api_opportunities_create_measure", options={"expose"=true})
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param Opportunity $opportunity
     *
     * @return JsonResponse
     */
    public function createMeasureAction(Request $request, Opportunity $opportunity)
    {
        $measure = new Measure();
        $form = $this->createForm(MeasureBaseType::class, $measure, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $measure->setOpportunity($opportunity);
            $measure->setResponsibility($this->getUser());
            $this->persistAndFlush($measure);

            return $this->createApiResponse($measure, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }
}
