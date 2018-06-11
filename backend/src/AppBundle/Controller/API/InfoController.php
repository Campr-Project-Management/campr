<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Info;
use AppBundle\Form\Info\ApiCreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/infos")
 */
class InfoController extends ApiController
{
    const ENTITY_CLASS = Info::class;
    const FORM_CLASS = ApiCreateType::class;

    /**
     * @Route("/{id}", name="app_api_infos_get", options={"expose": true})
     * @Method({"GET"})
     *
     * @param Info $info
     *
     * @return JsonResponse
     */
    public function getAction(Info $info)
    {
        return $this->createApiResponse($info);
    }

    /**
     * @Route("/{id}", name="app_api_infos_edit", options={"expose": true})
     * @Method({"PATCH", "PUT"})
     *
     * @param Request $request
     * @param Info    $info
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Info $info)
    {
        $form = $this->getForm($info, ['method' => $request->getMethod(), 'csrf_protection' => false]);

        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $this->persistAndFlush($info);

            return $this->createApiResponse($info, Response::HTTP_CREATED);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @Route("/{id}", name="app_api_infos_delete", options={"expose": true})
     * @Method({"DELETE"})
     *
     * @param Info $info
     *
     * @return JsonResponse
     */
    public function deleteAction(Info $info)
    {
        $em = $this->getEntityManager();
        $em->remove($info);
        $em->flush();

        return $this->createApiResponse(null);
    }
}
