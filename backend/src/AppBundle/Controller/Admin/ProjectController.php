<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\ChatRoom;
use AppBundle\Entity\FileSystem;
use AppBundle\Entity\Media;
use AppBundle\Entity\Message;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\User;
use AppBundle\Form\Project\ImportType;
use AppBundle\Security\ProjectVoter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\API\ApiController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Project;
use AppBundle\Form\Project\CreateType as ProjectCreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * Project admin controller.
 *
 * @Route("/admin/project")
 */
class ProjectController extends ApiController
{
    /**
     * Lists all Project entities.
     *
     * @Route("/list", name="app_admin_project_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projects = $em
            ->getRepository(Project::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Project:list.html.twig',
            [
                'projects' => $projects,
            ]
        );
    }

    /**
     * Lists all Project entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(Project::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/create", name="app_admin_project_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_list');
        }

        return $this->render(
            'AppBundle:Admin/Project:create.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_list');
        }

        return $this->render(
            'AppBundle:Admin/Project:edit.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a Project entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_show")
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return Response
     */
    public function showAction(Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->render(
            'AppBundle:Admin/Project:show.html.twig',
            [
                'project' => $project,
            ]
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_delete")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Project $project)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.project.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        } catch (\Exception $ex) {
            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';

            $message = [
                'delete' => 'failed',
                'message' => $flashMessage,
            ];
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                $flashKey,
                $flashMessage
            )
        ;

        return $this->redirectToRoute('app_admin_project_list');
    }

    /**
     * Project chat.
     *
     * @Route("/{id}/chat", name="app_admin_project_chat", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     *
     * @return Response
     */
    public function chatAction(Project $project)
    {
        $user = $this->getUser();
        $chatService = $this->get('app.service.chat');
        if ($project->getChatRooms()->isEmpty()) {
            $chatRoom = $chatService->createProjectChatRoom($project, ChatRoom::GENERAL_ROOM);
            $project->addChatRoom($chatRoom);
        }

        return $this->render(
            'AppBundle:Admin/Project:chat.html.twig',
            [
                'chat_list' => $chatService->getProjectChatList($project, $user),
                'project_id' => $project->getId(),
            ]
        );
    }

    /**
     * Project participants.
     *
     * @Route("/{id}/participants", name="app_admin_project_participants", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @return JsonResponse
     */
    public function participantsAction(Project $project)
    {
        $projectUsers = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(ProjectUser::class)
            ->findByProject($project)
        ;

        $names = [];
        /** @var ProjectUser $projectUser */
        foreach ($projectUsers as $projectUser) {
            $user = $projectUser->getUser();
            $info['id'] = $user->getId();
            $info['username'] = $user->getUsername();
            $info['roles'] = [];

            foreach ($projectUser->getProjectRoles() as $projectRole) {
                $info['roles'][] = $projectRole->getName();
            }

            $names[] = $info;
        }

        return new JsonResponse($names);
    }

    /**
     * Project chat messages.
     *
     * @Route(
     *     "/{project}/chat/{id}/messages",
     *     name="app_admin_project_chat_messages",
     *     options={"expose"=true}
     *     )
     *
     * @Method({"GET", "POST"})
     *
     * @param Project  $project
     * @param ChatRoom $chat
     *
     * @return JsonResponse
     */
    public function chatMessagesAction(Project $project, ChatRoom $chat)
    {
        $messages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Message::class)
            ->findBy(
                ['project' => $project, 'chatRoom' => $chat],
                ['createdAt' => 'ASC']
            )
        ;

        return new JsonResponse(
            $this->renderView(
                'AppBundle:Admin/Project/Partials:chat_messages.html.twig',
                [
                    'messages' => $messages,
                ]
            )
        );
    }

    /**
     * Project chat private messages.
     *
     * @Route(
     *     "/{project}/chat/{id}/private-messages",
     *     name="app_admin_project_chat_private_messages",
     *     options={"expose"=true}
     *     )
     *
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     * @param User    $user
     *
     * @return JsonResponse
     */
    public function chatPrivateMessagesAction(Project $project, User $user)
    {
        $currentUser = $this->getUser();
        $chatKey = $currentUser->getId() < $user->getId()
            ? $currentUser->getId().'-'.$user->getId()
            : $user->getId().'-'.$currentUser->getId()
        ;
        $messages = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Message::class)
            ->findUndeletedPrivateMessages($project, $currentUser, $chatKey)
        ;

        return new JsonResponse(
            $this->renderView(
                'AppBundle:Admin/Project/Partials:chat_messages.html.twig',
                [
                    'messages' => $messages,
                    'private' => true,
                ]
            )
        );
    }

    /**
     * Soft delete private messages.
     *
     * @Route(
     *     "/{project}/chat/{toUser}/delete-private-messages",
     *     name="app_admin_project_chat_delete_private_messages",
     *     options={"expose"=true}
     *     )
     *
     * @Method({"GET", "POST"})
     *
     * @param Project $project
     * @param User    $toUser
     *
     * @return JsonResponse
     */
    public function deletePrivateMessagesAction(Project $project, User $toUser)
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $chatKey = $currentUser->getId() < $toUser->getId()
            ? $currentUser->getId().'-'.$toUser->getId()
            : $toUser->getId().'-'.$currentUser->getId()
        ;
        $messages = $em
            ->getRepository(Message::class)
            ->findUndeletedPrivateMessages($project, $currentUser, $chatKey)
        ;

        foreach ($messages as $msg) {
            $msg->getFrom() == $currentUser
                ? $msg->setDeletedFromAt(new \DateTime())
                : $msg->setDeletedToAt(new \DateTime())
            ;
            $em->persist($msg);
        }

        $em->flush();

        return new JsonResponse(
            [
                'success' => $this
                    ->get('translator')
                    ->trans('success.chat_messages.delete', [], 'flashes'),
            ]
        );
    }

    /**
     * @Route("/import", name="app_admin_project_import", options={"expose"=true})
     *
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function importAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var File $file */
            $file = $form->get('file')->getData();

            $fileSystem = $this
                ->getDoctrine()
                ->getRepository(FileSystem::class)
                ->findOneBy([
                    'isDefault' => true,
                ])
            ;

            $media = (new Media())
                ->setFile($file)
                ->setUser($this->getUser())
                ->setPath($file->getClientOriginalName())
                ->setMimeType($file->getMimeType())
                ->setFileSize($file->getSize())
                ->setFileSystem($fileSystem)
            ;
            $em->persist($media);
            $em->flush();

            $env = $this->getParameter('kernel.environment');
            $redis = $this->get('redis.client');
            $redis->rpush(RedisQueueManagerCommand::IMPORT, [
                sprintf(
                    '--env=%s app:project-import %d',
                    $env,
                    $media->getId()
                ),
            ]);

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project.import', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_list');
        }

        return $this->render(
            'AppBundle:Admin/Project/Partials:import.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
