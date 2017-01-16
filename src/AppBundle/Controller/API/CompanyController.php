<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Company;
use AppBundle\Form\Company\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Company $company
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Company $company)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $company, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $company->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->createApiResponse($company);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
