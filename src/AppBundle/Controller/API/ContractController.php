<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Contract;
use AppBundle\Form\Contract\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/contract")
 */
class ContractController extends ApiController
{
    /**
     * Retrieve all Contracts.
     *
     * @Route("/list", name="app_api_contract_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $contracts = $this
            ->getDoctrine()
            ->getRepository(Contract::class)
            ->findAll()
        ;

        return $this->createApiResponse($contracts);
    }

    /**
     * Retrieve Contract information.
     *
     * @Route("/{id}", name="app_api_contract_get")
     * @Method({"GET"})
     *
     * @param Contract $contract
     *
     * @return JsonResponse
     */
    public function getAction(Contract $contract)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $contract->getProject());

        return $this->createApiResponse($contract);
    }

    /**
     * Create a new Contract.
     *
     * @Route("/create", name="app_api_contract_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $contract = new Contract();
        $form = $this->createForm(CreateType::class, $contract, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $contract->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->createApiResponse($contract, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Edit a specific Contract.
     *
     * @Route("/{id}/edit", name="app_api_contract_edit")
     * @Method({"PATCH"})
     *
     * @param Request  $request
     * @param Contract $contract
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Contract $contract)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $contract->getProject());

        $form = $this->createForm(CreateType::class, $contract, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();

            return $this->createApiResponse($contract, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Contract.
     *
     * @Route("/{id}/delete", name="app_api_contract_delete")
     * @Method({"DELETE"})
     *
     * @param Contract $contract
     *
     * @return JsonResponse
     */
    public function deleteAction(Contract $contract)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $contract->getProject());

        $em = $this->getDoctrine()->getManager();
        $em->remove($contract);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
