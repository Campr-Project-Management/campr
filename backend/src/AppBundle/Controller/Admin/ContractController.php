<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Contract;
use AppBundle\Security\ProjectVoter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Contract\CreateType as ContractCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Contract admin controller.
 *
 * @Route("/admin/contract")
 */
class ContractController extends BaseController
{
    /**
     * Lists all Contract entities.
     *
     * @Route("/list", name="app_admin_contract_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contracts = $em
            ->getRepository(Contract::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Contract:list.html.twig',
            [
                'contracts' => $contracts,
            ]
        );
    }

    /**
     * Lists all Contract entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_contract_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listFilteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Contract::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Contract entity.
     *
     * @Route("/create", name="app_admin_contract_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $contract = new Contract();
        $form = $this->createForm(ContractCreateType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contract->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.contract.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_contract_list');
        }

        return $this->render(
            'AppBundle:Admin/Contract:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Contract entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_contract_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param Contract $contract
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Contract $contract)
    {
        if ($project = $contract->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);
        }

        $form = $this->createForm(ContractCreateType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.contract.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_contract_show',
                [
                    'id' => $contract->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Admin/Contract:edit.html.twig',
            [
                'contract' => $contract,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Contract entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_contract_show")
     * @Method({"GET"})
     *
     * @param Contract $contract
     *
     * @return Response
     */
    public function showAction(Contract $contract)
    {
        if ($project = $contract->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);
        }

        return $this->render(
            'AppBundle:Admin/Contract:show.html.twig',
            [
                'contract' => $contract,
            ]
        );
    }

    /**
     * Deletes a Contract entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_contract_delete")
     * @Method({"GET"})
     *
     * @param Request  $request
     * @param Contract $contract
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Contract $contract)
    {
        if ($project = $contract->getProject()) {
            $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contract);
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
                    ->trans('success.contract.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_contract_list');
    }
}
