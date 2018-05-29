<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectCloseDown;
use AppBundle\Form\ProjectCloseDown\AdminType as ProjectCloseDownType;
use Symfony\Component\HttpFoundation\Response;

/**
 * ProjectCloseDown admin controller.
 *
 * @Route("/admin/project-close-down")
 */
class ProjectCloseDownController extends BaseController
{
    /**
     * Lists all Project Close Downs entities.
     *
     * @Route("/list", name="app_admin_project_close_down_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectCloseDowns = $em
            ->getRepository(ProjectCloseDown::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectCloseDown:list.html.twig',
            [
                'projectCloseDowns' => $projectCloseDowns,
            ]
        );
    }

    /**
     * Lists all ProjectCloseDown entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_close_down_filtered")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request)
    {
        $requestParams = $request->request->all();
        $dataTableService = $this->get('app.service.data_table');
        $response = $dataTableService->paginateByColumn(ProjectCloseDown::class, 'id', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectCloseDown entity.
     *
     * @Route("/create", name="app_admin_project_close_down_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectCloseDown = new ProjectCloseDown();
        $form = $this->createForm(ProjectCloseDownType::class, $projectCloseDown);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCloseDown);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_close_down.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_close_down_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCloseDown:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Project Close Down entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_close_down_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $form = $this->createForm(ProjectCloseDownType::class, $projectCloseDown);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectCloseDown);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_close_down.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_close_down_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectCloseDown:edit.html.twig',
            [
                'projectCloseDown' => $projectCloseDown,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a ProjectCloseDown entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_close_down_show")
     * @Method({"GET"})
     *
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return Response
     */
    public function showAction(ProjectCloseDown $projectCloseDown)
    {
        return $this->render(
            'AppBundle:Admin/ProjectCloseDown:show.html.twig',
            [
                'projectCloseDown' => $projectCloseDown,
            ]
        );
    }

    /**
     * Deletes a ProjectCloseDown entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_close_down_delete")
     * @Method({"GET"})
     *
     * @param Request          $request
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectCloseDown $projectCloseDown)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($projectCloseDown);
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
                    ->trans('success.project_close_down.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_project_close_down_list');
    }
}
