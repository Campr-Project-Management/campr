<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Assignment;
use AppBundle\Form\Assignment\CreateType;
use AppBundle\Form\Assignment\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Assignment controller.
 *
 * @Route("/admin/assignment")
 */
class AssignmentController extends Controller
{
    /**
     * Lists all Assignment entities.
     *
     * @Route("/list", name="app_admin_assignment_list")
     * @Method("GET")
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
        $response = $dataTableService->paginate(Assignment::class, 'name', $requestParams);

        return new JsonResponse($response);
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
                        ->trans('admin.assignment.create.success', [], 'admin')
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
        $form = $this->createForm(EditType::class, $assignment);
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
                        ->trans('admin.assignment.edit.success', [], 'admin')
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
     * Displays a Assignment entity.
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
     * @param Assignment $assignment
     * @param Request    $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Assignment $assignment, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($assignment);
        $em->flush();

        if ($request->isXmlHttpRequest()) {
            $message = [
                'delete' => 'success',
            ];

            return new JsonResponse($message, Response::HTTP_OK);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.assignment.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_assignment_list');
    }
}
