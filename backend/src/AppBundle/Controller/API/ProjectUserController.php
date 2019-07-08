<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use AppBundle\Form\ProjectUser\BaseCreateType;
use AppBundle\Security\ProjectVoter;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/project-users")
 */
class ProjectUserController extends ApiController
{
    /**
     * Get Project User by id.
     *
     * @Route("/{id}", name="app_api_project_users_get", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function getAction(ProjectUser $projectUser)
    {
        $project = $projectUser->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->createApiResponse($projectUser);
    }

    /**
     * Edit a specific Project User.
     *
     * @Route("/{id}", name="app_api_project_users_edit", options={"expose"=true})
     * @Method({"PUT", "PATCH"})
     *
     * @param Request     $request
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function editAction(Request $request, ProjectUser $projectUser)
    {
        $project = $projectUser->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(BaseCreateType::class, $projectUser, ['csrf_protection' => false]);
        $this->processForm($request, $form, $request->isMethod(Request::METHOD_PUT));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($projectUser);
            $em->flush();

            return $this->createApiResponse($projectUser, Response::HTTP_ACCEPTED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Delete a specific Project User.
     *
     * @Route("/{id}", name="app_api_project_users_delete", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function deleteAction(ProjectUser $projectUser)
    {
        $project = $projectUser->getProject();
        if (!$project) {
            throw new \LogicException('Project does not exist!');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $em = $this->getDoctrine()->getManager();
        $em->remove($projectUser);
        $em->flush();

        return $this->createApiResponse($projectUser);
    }

    /**
     * Delete a specific Project User.
     *
     * @Route("/{id}/{user}", name="app_api_project_users_delete_user", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Project $project
     * @param User    $user
     *
     * @return JsonResponse
     */
    public function deleteUserAction(Project $project, User $user)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $projectUser = $project
            ->getProjectUsers()
            ->filter(
                function (ProjectUser $projectUser) use ($user) {
                    return $projectUser->getUser() === $user;
                }
            )
            ->first();

        if (!$projectUser) {
            throw $this->createNotFoundException();
        }

        $this->get('app.repository.project_user')->remove($projectUser);

        return $this->createApiResponse($projectUser);
    }

    /**
     * Delete sponsor role.
     *
     * @Route("/{id}/sponsor/{projectUser}", name="app_api_project_users_delete_sponsor", options={"expose"=true})
     * @Method({"DELETE"})
     *
     * @param Project     $project
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function deleteSponsorAction(Project $project, ProjectUser $projectUser)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        try {
            $projectUser->removeProjectRoleByName(ProjectRole::ROLE_SPONSOR);
            $this->get('app.repository.project_user')->add($projectUser);
        } catch (\Exception $e) {
            return $this->createApiResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createApiResponse(['items' => $project->getProjectSponsors()]);
    }

    /**
     * Update project sponsor.
     *
     * @Route("/{id}/sponsor/{projectUser}", name="app_api_project_users_update_sponsor", options={"expose"=true})
     * @Method({"PATCH"})
     *
     * @param Project     $project
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function updateSponsorAction(Project $project, ProjectUser $projectUser)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $projectRoleRepository = $this->get('app.repository.project_role');
        $role = $projectRoleRepository->getSponsor($project);

        if (!$role) {
            $role = new ProjectRole();
            $role->setProject($project);
            $role->setName(ProjectRole::ROLE_SPONSOR);
            $projectRoleRepository->add($role);
        }

        try {
            foreach ($project->getProjectSponsors() as $projectSponsor) {
                $projectSponsor->removeProjectRoleByName(ProjectRole::ROLE_SPONSOR);
                $this->get('app.repository.project_user')->add($projectSponsor);
            }

            $projectUser->addProjectRole($role);
            $this->get('app.repository.project_user')->add($projectUser);
        } catch (\Exception $e) {
            return $this->createApiResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createApiResponse(['items' => $project->getProjectSponsors()]);
    }

    /**
     * Create project sponsor.
     *
     * @Route("/{id}/sponsor/{projectUser}", name="app_api_project_users_create_sponsor", options={"expose"=true})
     * @Method({"PUT"})
     *
     * @param Project     $project
     * @param ProjectUser $projectUser
     *
     * @return JsonResponse
     */
    public function createSponsorAction(Project $project, ProjectUser $projectUser)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $projectRoleRepository = $this->get('app.repository.project_role');
        $role = $projectRoleRepository->getSponsor($project);

        if (!$role) {
            $role = new ProjectRole();
            $role->setProject($project);
            $role->setName(ProjectRole::ROLE_SPONSOR);
            $projectRoleRepository->add($role);
        }

        $projectUser->addProjectRole($role);
        $this->get('app.repository.project_user')->add($projectUser);

        return $this->createApiResponse(['items' => $project->getProjectSponsors()]);
    }
}
