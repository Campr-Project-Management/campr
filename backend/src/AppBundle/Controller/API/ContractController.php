<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Contract;
use AppBundle\Form\Contract\BaseCreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/contracts")
 */
class ContractController extends ApiController
{
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
     * Edit a specific Contract.
     *
     * @Route("/{id}", name="app_api_contract_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request  $request
     * @param Contract $contract
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Contract $contract)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $contract->getProject());

        $form = $this->createForm(BaseCreateType::class, $contract, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

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
     * @Route("/{id}", name="app_api_contract_delete")
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
