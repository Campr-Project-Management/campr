<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectComplexity;
use AppBundle\Form\ProjectComplexity\CreateType as ProjectComplexityCreateType;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

/**
 * ProjectComplexity admin controller.
 *
 * @Route("/admin/project-complexity")
 */
class ProjectComplexityController extends BaseController
{
    /**
     * Lists all ProjectComplexity entities.
     *
     * @Route("/list", name="app_admin_project_complexity_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projectComplexities = $em
            ->getRepository(ProjectComplexity::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:list.html.twig',
            [
                'project_complexities' => $projectComplexities,
            ]
        );
    }

    /**
     * Lists all ProjectComplexity entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_project_complexity_list_filtered")
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
        $response = $dataTableService->paginateByColumn(ProjectComplexity::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new ProjectComplexity entity.
     *
     * @Route("/create", name="app_admin_project_complexity_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $projectComplexity = new ProjectComplexity();
        $form = $this->createForm(ProjectComplexityCreateType::class, $projectComplexity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_complexity.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_complexity_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing ProjectComplexity entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_project_complexity_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request           $request
     * @param ProjectComplexity $projectComplexity
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, ProjectComplexity $projectComplexity)
    {
        $form = $this->createForm(ProjectComplexityCreateType::class, $projectComplexity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectComplexity->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($projectComplexity);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.project_complexity.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_project_complexity_list');
        }

        return $this->render(
            'AppBundle:Admin/ProjectComplexity:edit.html.twig',
            [
                'project_complexity' => $projectComplexity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a ProjectComplexity entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_project_complexity_show")
     * @Method({"GET"})
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return Response
     */
    public function showAction(ProjectComplexity $projectComplexity)
    {
        return $this->render(
            'AppBundle:Admin/ProjectComplexity:show.html.twig',
            [
                'project_complexity' => $projectComplexity,
            ]
        );
    }

    /**
     * Deletes a ProjectComplexity entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_project_complexity_delete")
     * @Method({"GET"})
     *
     * @param Request           $request
     * @param ProjectComplexity $projectComplexity
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, ProjectComplexity $projectComplexity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projectComplexity);
            $em->flush();

            $message = [
                'delete' => 'success',
            ];
            $flashMessage = $this
                ->get('translator')
                ->trans('success.project_complexity.delete.from_edit', [], 'flashes')
            ;
            $flashKey = 'success';
        } catch (ForeignKeyConstraintViolationException $ex) {
            $message = [
                'delete' => 'failed',
            ];

            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project_complexity.delete.dependency_constraint', [], 'flashes')
            ;
            $flashKey = 'failed';
        } catch (\Exception $ex) {
            $message = [
                'delete' => 'failed',
            ];

            $flashMessage = $this
                ->get('translator')
                ->trans('failed.project_complexity.delete.generic', [], 'flashes')
            ;
            $flashKey = 'failed';
        }

        if ($request->isXmlHttpRequest()) {
            $message['message'] = $flashMessage;

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

        return $this->redirectToRoute('app_admin_project_complexity_list');
    }
}
