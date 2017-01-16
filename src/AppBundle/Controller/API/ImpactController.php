<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Impact;
use AppBundle\Form\Impact\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/impact")
 */
class ImpactController extends ApiController
{
    /**
     * Get all impacts.
     *
     * @Route("/list", name="app_api_impact_list")
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
     * @Route("/create", name="app_api_impact_create")
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
     * @Route("/{id}/edit", name="app_api_impact_edit")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param Impact  $impact
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Impact $impact)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $impact, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($impact);
            $em->flush();

            return $this->createApiResponse($impact);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Impact.
     *
     * @Route("/{id}/delete", name="app_api_impact_delete")
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

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
