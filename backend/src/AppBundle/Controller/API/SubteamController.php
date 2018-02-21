<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Subteam;
use AppBundle\Form\Subteam\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/subteams")
 */
class SubteamController extends ApiController
{
    const ENTITY_CLASS = Subteam::class;
    const FORM_CLASS = CreateType::class;

    /**
     * @Route("/{id}", name="app_api_subteam_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Subteam $subteam
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Subteam $subteam)
    {
        $form = $this->getForm($subteam, ['csrf_protection' => false]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($subteam);

            return $this->createApiResponse($subteam, JsonResponse::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_subteam_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Subteam $subteam)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subteam);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
