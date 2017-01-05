<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\ProjectUser;
use AppBundle\Form\ProjectUser\CreateType;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/project-user")
 */
class ProjectUserController extends ApiController
{
    /**
     * Get all project users.
     *
     * @Route("/list", name="app_api_project_user_list")
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function listAction()
    {
        $projectUsers = $this
            ->getDoctrine()
            ->getRepository(ProjectUser::class)
            ->findAll()
        ;

        return $this->createApiResponse($projectUsers);
    }

    /**
     * Create a new Project User.
     *
     * @Route("/create", name="app_api_project_user_create")
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
     * Get Project User by id.
     *
     * @Route("/{id}", name="app_api_project_user_get")
     * @Method({"GET"})
     *
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function getAction(ProjectUser $projectUser)
    {
        return $this->createApiResponse($projectUser);
    }

    /**
     * Edit a specific Project User.
     *
     * @Route("/{id}/edit", name="app_api_project_user_edit")
     * @Method({"POST"})
     *
     * @param Request     $request
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectUser $projectUser)
    {
        $data = $request->request->all();
        $form = $this->createForm(CreateType::class, $projectUser, ['csrf_protection' => false]);
        $form->submit($data, false);

        if ($form->isValid()) {
            $projectUser->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectUser);
            $em->flush();

            return $this->createApiResponse($projectUser);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $this->createApiResponse($errors);
    }

    /**
     * Delete a specific Project User.
     *
     * @Route("/{id}/delete", name="app_api_project_user_delete")
     * @Method({"DELETE"})
     *
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectUser $projectUser)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectUser);
        $em->flush();

        return $this->createApiResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
