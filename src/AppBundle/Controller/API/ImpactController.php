<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Impact;
use AppBundle\Form\Impact\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/impacts")
 */
class ImpactController extends ApiController
{
    /**
     * Get all impacts.
     *
     * @Route(name="app_api_impact_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $impacts = $this
            ->getDoctrine()
            ->getRepository(Impact::class)
            ->findAll()
        ;

        return $this->createApiResponse($impacts);
    }

    /**
     * Create a new Impact.
     *
     * @Route(name="app_api_impact_create")
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
     * Get Impact by id.
     *
     * @Route("/{id}", name="app_api_impact_get")
     * @Method({"GET"})
     *
     * @param Impact $impact
     *
     * @return JsonResponse
     */
    public function getAction(Impact $impact)
    {
        return $this->createApiResponse($impact);
    }

    /**
     * Edit a specific Impact.
     *
     * @Route("/{id}", name="app_api_impact_edit")
     * @Method({"PUT", "PATCH"})
     *
     * @param Request $request
     * @param Impact  $impact
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Impact $impact)
    {
        $form = $this->createForm(CreateType::class, $impact, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($impact);
            $em->flush();

            return $this->createApiResponse($impact, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Impact.
     *
     * @Route("/{id}", name="app_api_impact_delete")
     * @Method({"DELETE"})
     *
     * @param Impact $impact
     *
     * @return JsonResponse
     */
    public function deleteAction(Impact $impact)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($impact);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
