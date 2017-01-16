<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectComplexity;
use AppBundle\Form\ProjectComplexity\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-complexity")
 */
class ProjectComplexityController extends ApiController
{
    /**
     * Get all project complexities.
     *
     * @Route("/list", name="app_api_project_complexity_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectComplexities = $this
            ->getDoctrine()
            ->getRepository(ProjectComplexity::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectComplexities);
    }

    /**
     * Create a new Project Complexity.
     *
     * @Route("/create", name="app_api_project_complexity_create")
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
     * Get Project Complexity by id.
     *
     * @Route("/{id}", name="app_api_project_complexity_get")
     * @Method({"GET"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function getAction(ProjectComplexity $projectComplexity)
    {
        return $this->createApiResponse($projectComplexity);
    }

    /**
     * Edit a specific Project Complexity.
     *
     * @Route("/{id}/edit", name="app_api_project_complexity_edit")
     * @Method({"POST"})
     *
     * @param Request           $request
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectComplexity $projectComplexity)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectComplexity, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectComplexity->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            return $this->createApiResponse($projectComplexity);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project Complexity.
     *
     * @Route("/{id}/delete", name="app_api_project_complexity_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectComplexity $projectComplexity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectComplexity);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
