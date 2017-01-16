<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\RiskCategory;
use AppBundle\Form\RiskCategory\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/risk-category")
 */
class RiskCategoryController extends ApiController
{
    /**
     * Get all risk categories.
     *
     * @Route("/list", name="app_api_risk_category_list")
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
     * @Route("/create", name="app_api_risk_category_create")
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
     * Get Risk Category by id.
     *
     * @Route("/{id}", name="app_api_risk_category_get")
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
     * @Route("/{id}/edit", name="app_api_risk_category_edit")
     * @Method({"POST"})
     *
     * @param Request      $request
     * @param RiskCategory $riskCategory
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, RiskCategory $riskCategory)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $riskCategory, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($riskCategory);
            $em->flush();

            return $this->createApiResponse($riskCategory);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Risk Category.
     *
     * @Route("/{id}/delete", name="app_api_risk_category_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
