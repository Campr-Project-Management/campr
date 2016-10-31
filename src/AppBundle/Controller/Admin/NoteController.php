<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Note;
use AppBundle\Form\Note\CreateType;
use AppBundle\Form\Note\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * NoteController controller.
 *
 * @Route("/admin/note")
 */
class NoteController extends Controller
{
    /**
     * Lists all Meeting entities.
     *
     * @Route("/list", name="app_admin_note_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notes = $em
            ->getRepository(Note::class)
            ->findAll();

        return $this->render(
            'AppBundle:Admin/Note:list.html.twig',
            [
                'notes' => $notes,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_note_list_filtered")
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
        $response = $dataTableService->paginateByColumn(Note::class, 'title', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Note entity.
     *
     * @Route("/{id}/show", name="app_admin_note_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Note $note
     *
     * @return Response
     */
    public function showAction(Note $note)
    {
        return $this->render(
            'AppBundle:Admin/Note:show.html.twig',
            [
                'note' => $note,
            ]
        );
    }

    /**
     * Creates a new Note entity.
     *
     * @Route("/create", name="app_admin_note_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($form->getData());
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.note.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_note_list');
        }

        return $this->render(
            'AppBundle:Admin/Note:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_note_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Note    $note
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Note $note, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($note);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.note.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_note_list');
        }

        return $this->render(
            'AppBundle:Admin/Note:edit.html.twig',
            [
                'id' => $note->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_note_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Note    $note
     * @param Request $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Note $note, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($note);
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
                    ->trans('admin.note.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_note_list');
    }
}
