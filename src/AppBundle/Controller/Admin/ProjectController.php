<?php

namespace AppBundle\Controller\Admin;

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
 * Project admin controller.
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
     * @param Request $request
     * @param Project $project
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
                    ->trans('admin.chat.delete_messages', [], 'admin'),
            ]
        );
    }
}
