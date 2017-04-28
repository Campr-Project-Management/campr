<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\SubteamRole;
use AppBundle\Form\SubteamRole\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/subteam-roles")
 */
class SubteamRoleController extends ApiController
{
    const ENTITY_CLASS = SubteamRole::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("", name="app_api_subteam_role")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->createApiResponse($this->getRepository()->findAll());
    }

    /**
     * @Route("", name="app_api_subteam_role_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $subteamRole = new SubteamRole();
        $form = $this->getForm($subteamRole, ['csrf_protection' => false]);

        $this->processForm($request, $form);

        if ($form->isValid()) {
            $this->persistAndFlush($subteamRole);

            return $this->createApiResponse($subteamRole, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_subteam_role_edit")
     * @Method({"PUT", "PATCH"})
     */
    public function editAction(Request $request, SubteamRole $subteamRole)
    {
        $form = $this->getForm($subteamRole, ['csrf_protection' => false]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($subteamRole);

            return $this->createApiResponse($subteamRole, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_subteam_role_delete")
     * @Method({"GET"})
     */
    public function deleteAction(SubteamRole $subteamRole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subteamRole);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
