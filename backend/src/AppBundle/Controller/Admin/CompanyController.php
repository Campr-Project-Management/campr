<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Company;
use AppBundle\Form\Company\CreateType as CompanyCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Company admin controller.
 *
 * @Route("/admin/company")
 */
class CompanyController extends BaseController
{
    /**
     * Lists all Company entities.
     *
     * @Route("/list", name="app_admin_company_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $companies = $em
            ->getRepository(Company::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Company:list.html.twig',
            [
                'companies' => $companies,
            ]
        );
    }

    /**
     * Lists all Company entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_company_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Company::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/create", name="app_admin_company_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyCreateType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.company.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_company_list');
        }

        return $this->render(
            'AppBundle:Admin/Company:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_company_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Company $company
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Company $company)
    {
        $form = $this->createForm(CompanyCreateType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.company.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_company_list');
        }

        return $this->render(
            'AppBundle:Admin/Company:edit.html.twig',
            [
                'company' => $company,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_company_show")
     * @Method({"GET"})
     *
     * @param Company $company
     *
     * @return Response
     */
    public function showAction(Company $company)
    {
        return $this->render(
            'AppBundle:Admin/Company:show.html.twig',
            [
                'company' => $company,
            ]
        );
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_company_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Company $company
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Company $company)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($company);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.company.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_company_list');
    }
}
