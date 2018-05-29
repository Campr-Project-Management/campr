<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Assignment;
use AppBundle\Form\Assignment\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Assignment admin controller.
 *
 * @Route("/admin/assignment")
 */
class AssignmentController extends BaseController
{
    /**
     * Lists all Assignment entities.
     *
     * @Route("/list", name="app_admin_assignment_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $assignments = $em
            ->getRepository(Assignment::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Assignment:list.html.twig',
            [
                'assignments' => $assignments,
            ]
        );
    }

    /**
     * Lists all Assignment entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_assignment_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Assignment::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new Assignment entity.
     *
     * @Route("/create", name="app_admin_assignment_create")
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
                        ->trans('success.assignment.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_assignment_list');
        }

        return $this->render(
            'AppBundle:Admin/Assignment:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Assignment entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_assignment_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param Assignment $assignment
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Assignment $assignment)
    {
        $form = $this->createForm(CreateType::class, $assignment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($assignment);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.assignment.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_assignment_list');
        }

        return $this->render(
            'AppBundle:Admin/Assignment:edit.html.twig',
            [
                'id' => $assignment->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays an Assignment entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_assignment_show")
     * @Method({"GET"})
     *
     * @param Assignment $assignment
     *
     * @return Response
     */
    public function showAction(Assignment $assignment)
    {
        return $this->render(
            'AppBundle:Admin/Assignment:show.html.twig',
            [
                'assignment' => $assignment,
            ]
        );
    }

    /**
     * Deletes a Assignment entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_assignment_delete")
     * @Method({"GET"})
     *
     * @param Request    $request
     * @param Assignment $assignment
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Assignment $assignment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($assignment);
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
                    ->trans('success.assignment.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_assignment_list');
    }
}
