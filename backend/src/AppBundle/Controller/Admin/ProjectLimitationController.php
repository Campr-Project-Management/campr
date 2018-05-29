<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ProjectLimitation;
use AppBundle\Security\ProjectVoter;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\ProjectLimitation\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectLimitation admin controller.
 *
 * @Route("/admin/project-limitation")
 */
class ProjectLimitationController extends BaseController
{
    /**
     * Lists all ProjectLimitation entities.
     *
     * @Route("/list", name="app_admin_project_limitation_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectLimitations = $em
            ->getRepository(ProjectLimitation::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectLimitation:list.html.twig',
            [
                'projectLimitations' => $projectLimitations,
            ]
        );
    }

    /**
     * Lists all ProjectLimitation entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_limitation_list_filtered")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listFilteredAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(ProjectLimitation::class, 'description', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectLimitation entity.
     *
     * @Route("/create", name="app_admin_project_limitation_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_limitation.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_limitation_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectLimitation:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectLimitation entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_limitation_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param ProjectLimitation $projectLimitation
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $projectLimitation->getProject());
        $form = $this->createForm(CreateType::class, $projectLimitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectLimitation);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_limitation.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute(
                'app_admin_project_limitation_show',
                [
                    'id' => $projectLimitation->getId(),
                ]
            );
        }

        return $this->render(
            'AppBundle:Admin/ProjectLimitation:edit.html.twig',
            [
                'id' => $projectLimitation->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a ProjectLimitation entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_limitation_show")
     * @Method({"GET"})
     *
     * @param ProjectLimitation $projectLimitation
     *
     * @return Response
     */
    public function showAction(ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $projectLimitation->getProject());

        return $this->render(
            'AppBundle:Admin/ProjectLimitation:show.html.twig',
            [
                'projectLimitation' => $projectLimitation,
            ]
        );
    }

    /**
     * Deletes a ProjectLimitation entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_limitation_delete")
     * @Method({"GET"})
     *
     * @param Request           $request
     * @param ProjectLimitation $projectLimitation
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectLimitation $projectLimitation)
    {
        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $projectLimitation->getProject());
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectLimitation);
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
                    ->trans('success.project_limitation.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_limitation_list');
    }
}
