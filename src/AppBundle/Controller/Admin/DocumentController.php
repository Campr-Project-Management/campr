<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Document;
use AppBundle\Form\Document\CreateType;
use AppBundle\Form\Document\EditType;
use Symfony\Component\HttpFoundation\Response;

/**
 * DocumentController controller.
 *
 * @Route("/admin/document")
 */
class DocumentController extends Controller
{
    /**
     * Lists all Document entities.
     *
     * @Route("/list", name="app_admin_document_list")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $documents = $em
            ->getRepository(Document::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Document:list.html.twig',
            [
                'documents' => $documents,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_document_list_filtered")
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
        $response = $dataTableService->paginate(Document::class, 'fileName', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Displays Document entity.
     *
     * @Route("/{id}/show", name="app_admin_document_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Document $document
     *
     * @return Response
     */
    public function showAction(Document $document)
    {
        return $this->render(
            'AppBundle:Admin/Document:show.html.twig',
            [
                'document' => $document,
            ]
        );
    }

    /**
     * Creates a new Document entity.
     *
     * @Route("/create", name="app_admin_document_create", options={"expose"=true})
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
            $document = $form->getData();
            $documentFile = $document->getFile();
            $documentFile->move($this->getParameter('documents_upload_folder'), $documentFile->getClientOriginalName());
            $document->setFileName($documentFile->getClientOriginalName());
            $em->persist($document);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.document.create.success', [], 'admin')
                );

            return $this->redirectToRoute('app_admin_document_list');
        }

        return $this->render(
            'AppBundle:Admin/Document:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="app_admin_document_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Document $document
     * @param Request  $request
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Document $document, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($document);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.document.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_document_list');
        }

        return $this->render(
            'AppBundle:Admin/Document:edit.html.twig',
            [
                'document' => $document,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="app_admin_document_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Document $document
     * @param Request  $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Document $document, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($document);
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
                    ->trans('admin.document.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_document_list');
    }
}
