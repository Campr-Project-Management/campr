<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\RiskCategory;
use AppBundle\Form\RiskCategory\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/risk-categories")
 */
class RiskCategoryController extends ApiController
{
    /**
     * Get all risk categories.
     *
     * @Route(name="app_api_risk_categories_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $riskCategories = $this
            ->getDoctrine()
            ->getRepository(RiskCategory::class)
            ->findAll()
        ;

        return $this->createApiResponse($riskCategories);
    }

    /**
     * Create a new Risk Category.
     *
     * @Route(name="app_api_risk_categories_create")
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
     * Get Risk Category by id.
     *
     * @Route("/{id}", name="app_api_risk_categories_get")
     * @Method({"GET"})
     *
     * @param RiskCategory $riskCategory
     *
     * @return JsonResponse
     */
    public function getAction(RiskCategory $riskCategory)
    {
        return $this->createApiResponse($riskCategory);
    }

    /**
     * Edit a specific Risk Category.
     *
     * @Route("/{id}", name="app_api_risk_categories_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request      $request
     * @param RiskCategory $riskCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, RiskCategory $riskCategory)
    {
        $form = $this->createForm(CreateType::class, $riskCategory, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($riskCategory);
            $em->flush();

            return $this->createApiResponse($riskCategory, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Risk Category.
     *
     * @Route("/{id}", name="app_api_risk_categories_delete")
     * @Method({"DELETE"})
     *
     * @param RiskCategory $riskCategory
     *
     * @return JsonResponse
     */
    public function deleteAction(RiskCategory $riskCategory)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($riskCategory);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
