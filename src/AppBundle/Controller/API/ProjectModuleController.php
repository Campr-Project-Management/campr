<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectModule;
use AppBundle\Form\ProjectModule\CreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-module")
 */
class ProjectModuleController extends ApiController
{
    /**
     * Get all project modules.
     *
     * @Route("/list", name="app_api_project_module_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectModules = $this
            ->getDoctrine()
            ->getRepository(ProjectModule::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectModules);
    }

    /**
     * Create a new Project Module.
     *
     * @Route("/create", name="app_api_project_module_create")
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
     * Get Project Module by id.
     *
     * @Route("/{id}", name="app_api_project_module_get")
     * @Method({"GET"})
     *
     * @param ProjectModule $projectModule
     *
     * @return JsonResponse
     */
    public function getAction(ProjectModule $projectModule)
    {
        $project = $projectModule->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->createApiResponse($projectModule);
    }

    /**
     * Edit a specific Project Module.
     *
     * @Route("/{id}/edit", name="app_api_project_module_edit")
     * @Method({"PATCH"})
     *
     * @param Request       $request
     * @param ProjectModule $projectModule
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectModule $projectModule)
    {
        $project = $projectModule->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(CreateType::class, $projectModule, ['csrf_protection' => false]);
        $this->processForm($request, $form, false);

        if ($form->isValid()) {
            $projectModule->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectModule);
            $em->flush();

            return $this->createApiResponse($projectModule, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project Module.
     *
     * @Route("/{id}/delete", name="app_api_project_module_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectModule $projectModule
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectModule $projectModule)
    {
        $project = $projectModule->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectModule);
        $em->flush();

        return $this->createApiResponse(null, Response::HTTP_NO_CONTENT);
    }
}
