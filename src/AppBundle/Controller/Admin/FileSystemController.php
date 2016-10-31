<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\FileSystem;
use AppBundle\Form\FileSystem\CreateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\File;

/**
 * FileSystem controller.
 *
 * @Route("/admin/file-system")
 */
class FileSystemController extends Controller
{
    /**
     * Lists all FileSystem entities.
     *
     * @Route("/list", name="app_admin_filesystem_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();

        $filesystems = $em
            ->getRepository(FileSystem::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/FileSystem:list.html.twig',
            [
                'filesystems' => $filesystems,
            ]
        );
    }

    /**
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_filesystem_list_filtered")
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
        $response = $dataTableService->paginateByColumn(FileSystem::class, 'name', $requestParams);

        return new JsonResponse($response);
    }

    /**
     * Creates a new FileSystem entity.
     *
     * @Route("/create", name="app_admin_filesystem_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CreateType::class, new FileSystem());
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView(
                'AppBundle:Admin/FileSystem/Partials:form.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new JsonResponse($html);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $fileSystem = $form->getData();
            $fileSystem->setConfig($form->get('adapter')->getData());

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
                        ->trans('admin.filesystem.create.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_filesystem_list');
        }

        return $this->render(
            'AppBundle:Admin/FileSystem:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing FileSystem entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_filesystem_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param FileSystem $fileSystem
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, FileSystem $fileSystem)
    {
        $form = $this->createForm(CreateType::class, $fileSystem);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $html = $this->renderView(
                'AppBundle:Admin/FileSystem/Partials:form.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new JsonResponse($html);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $fileSystem->setConfig($form->get('adapter')->getData());

            $em->persist($fileSystem);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('admin.filesystem.edit.success', [], 'admin')
                )
            ;

            return $this->redirectToRoute('app_admin_filesystem_list');
        }

        return $this->render(
            'AppBundle:Admin/FileSystem:edit.html.twig',
            [
                'id' => $fileSystem->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a FileSystem entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_filesystem_show")
     * @Method({"GET"})
     *
     * @param FileSystem $fileSystem
     *
     * @return Response
     */
    public function showAction(FileSystem $fileSystem)
    {
        return $this->render(
            'AppBundle:Admin/FileSystem:show.html.twig',
            [
                'fileSystem' => $fileSystem,
            ]
        );
    }

    /**
     * Deletes a FileSystem entity.
     *
     * @Route("/{id}", options={"expose"=true}, name="app_admin_filesystem_delete")
     * @Method({"GET"})
     *
     * @param FileSystem $fileSystem
     * @param Request    $request
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(FileSystem $fileSystem, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($fileSystem);
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
                    ->trans('admin.filesystem.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_filesystem_list');
    }
}
