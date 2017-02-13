<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Company;
use AppBundle\Form\Company\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/company")
 */
class CompanyController extends ApiController
{
    /**
     * Get all companies.
     *
     * @Route("/list", name="app_api_company_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $companies = $this
            ->getDoctrine()
            ->getRepository(Company::class)
            ->findAll()
        ;

        return $this->createApiResponse($companies);
    }

    /**
     * Create a new Company.
     *
     * @Route("/create", name="app_api_company_create")
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
     * Get Company by id.
     *
     * @Route("/{id}", name="app_api_company_get")
     * @Method({"GET"})
     *
     * @param Company $company
     *
     * @return JsonResponse
     */
    public function getAction(Company $company)
    {
        return $this->createApiResponse($company);
    }

    /**
     * Edit a specific Company.
     *
     * @Route("/{id}/edit", name="app_api_company_edit")
     * @Method({"PATCH"})
     *
     * @param Request $request
     * @param Company $company
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Company $company)
    {
        $form = $this->createForm(CreateType::class, $company, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $company->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->createApiResponse($company, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Company.
     *
     * @Route("/{id}/delete", name="app_api_company_delete")
     * @Method({"DELETE"})
     *
     * @param Company $company
     *
     * @return JsonResponse
     */
    public function deleteAction(Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
