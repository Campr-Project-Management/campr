<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Decision;
use AppBundle\Entity\Media;
use AppBundle\Form\Decision\ApiCreateType;
use AppBundle\Form\Decision\CreateType;
use Doctrine\Common\Collections\ArrayCollection;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/decisions")
 */
class DecisionController extends ApiController
{
    const ENTITY_CLASS = Decision::class;
    const FORM_CLASS = CreateType::class;

    /**
     * Retrieve decision information.
     *
     * @Route("/{id}", name="app_api_decisions_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function getAction(Decision $decision)
    {
        return $this->createApiResponse($decision);
    }

    /**
     * Edit a specific Decision.
     *
     * @Route("/{id}", name="app_api_decisions_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH", "POST"})
     *
     * @param Request  $request
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, Decision $decision)
    {
        $form = $this->createForm(
            ApiCreateType::class,
            $decision,
            [
                'csrf_protection' => false,
                'method' => $request->getMethod(),
                'validation_groups' => ['Default', 'edit'],
            ]
        );

        $originalMedias = new ArrayCollection();
        foreach ($decision->getMedias() as $media) {
            $originalMedias->add($media);
        }

        $this->processForm($request, $form, !$request->isMethod(Request::METHOD_PATCH));

        if (!$form->isValid()) {
            $errors = $this->getFormErrors($form);
            $errors = [
                'messages' => $errors,
            ];

            return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();

        /** @var Decision $decision */
        $decision = $form->getData();

        foreach ($decision->getMedias() as $media) {
            $media->makeAsPermanent();
        }

        /** @var Media $media */
        foreach ($originalMedias as $media) {
            if (!$decision->getMedias()->contains($media)) {
                $media->makeAsTemporary(0);
                $em->persist($media);
            }
        }

        $this->get('app.repository.decisions')->add($decision);

        return $this->createApiResponse($decision, Response::HTTP_ACCEPTED);
    }

    /**
     * Delete a specific Decision.
     *
     * @Route("/{id}", name="app_api_decisions_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Decision $decision
     *
     * @return JsonResponse
     */
    public function deleteAction(Decision $decision)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($decision);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
