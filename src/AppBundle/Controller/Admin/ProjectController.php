<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Media;
use AppBundle\Form\FileSystem\MediaUploadType;
use AppBundle\Entity\ChatRoom;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Project;
use AppBundle\Form\Project\CreateType as ProjectCreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Project controller.
 *
 * @Route("/admin/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/list", name="app_admin_project_list")
     * @Method("GET")
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

        return new JsonResponse($response);
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
                        ->trans('admin.project.create.success', [], 'admin')
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
        $form = $this->createForm(ProjectCreateType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUpdatedAt(new \DateTime());

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
                        ->trans('admin.project.edit.success', [], 'admin')
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
     * @param Project $project
     * @param Request $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Project $project)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.project.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_list');
    }

    /**
     * Display project related files.
     *
     * @Route("/{id}/files", name="app_admin_project_files", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     *
     * @return Response
     */
    public function filesAction(Project $project)
    {
        $ids = [];
        foreach ($project->getFileSystems() as $fs) {
            $ids[] = $fs->getId();
        }
        $files = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(Media::class)
            ->findByFileSystem($ids)
        ;

        return $this->render(
            'AppBundle:Admin/Project:files.html.twig',
            [
                'id' => $project->getId(),
                'files' => $files,
            ]
        );
    }

    /**
     * Display filtered project files.
     *
     * @Route("/{id}/files/filtered", options={"expose"=true}, name="app_admin_project_files_filtered")
     * @Method("POST")
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function filesFilteredAction(Request $request, Project $project)
    {
        $fsIds = [];
        foreach ($project->getFileSystems() as $fs) {
            $fsIds[] = $fs->getId();
        }
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(
            Media::class,
            'path',
            $requestParams,
            [
                'countFunction' => 'countTotalByFileSystem',
                'countArguments' => [$fsIds],
                'findIn' => [
                    'fileSystem' => $fsIds,
                ],
            ]
        );

        return new JsonResponse($response);
    }

    /**
     * Upload new file to a filesystem.
     *
     * @Route("/{id}/upload", name="app_admin_project_upload")
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response
     */
    public function uploadAction(Request $request, Project $project)
    {
        $media = new Media();
        $form = $this->createForm(MediaUploadType::class, $media, ['project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($form->getData());
                $em->flush();
            } catch (\Exception $ex) {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set(
                        'error',
                        $this
                            ->get('translator')
                            ->trans('admin.project.files.upload.failed', [], 'admin')
                    )
                ;
            }
        }

        return $this->render(
            'AppBundle:Admin/Project:upload.html.twig',
            [
                'project_id' => $project->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Remove a file from a project.
     *
     * @Route("/{id}/remove-file", name="app_admin_project_remove_file", options={"expose"=true})
     *
     * @param Request $request
     * @param Media   $media
     *
     * @return JsonResponse|RedirectResponse
     */
    public function removeFileAction(Request $request, Media $media)
    {
        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            try {
                $em->remove($media);
                $em->flush();
                $message = [
                    'delete' => 'success',
                ];
            } catch (\Exception $ex) {
                $message = [
                    'delete' => 'failed',
                ];
            }

            return new JsonResponse($message);
        }

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
        // TODO: add condition to retrieve only project participants.
        $users = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository(User::class)
            ->findAll()
        ;

        $names = [];
        foreach ($users as $user) {
            if ($user !== $this->getUser()) {
                $info['id'] = $user->getId();
                $info['username'] = $user->getUsername();
                $names[] = $info;
            }
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
     * @param User    $user
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
                    ->trans('admin.chat.delete_messages', [], 'admin'),
            ]
        );
    }
}
