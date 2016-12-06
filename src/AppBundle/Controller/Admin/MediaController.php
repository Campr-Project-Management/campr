<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Media;
use AppBundle\Entity\Project;
use AppBundle\Form\FileSystem\MediaUploadType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Media controller for project files.
 *
 * @Route("/admin/project")
 */
class MediaController extends Controller
{
    /**
     * Lists all Media entities within project.
     *
     * @Route("/{project}/media/list", name="app_admin_project_media_list", options={"expose"=true})
     * @Method("GET")
     *
     * @param Project $project
     *
     * @return Response
     */
    public function listAction(Project $project)
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
            'AppBundle:Admin/Media:list.html.twig',
            [
                'project_name' => $project->getName(),
                'project_id' => $project->getId(),
                'files' => $files,
            ]
        );
    }

    /**
     * @Route("/{project}/media/list/filtered", name="app_admin_project_media_list_filtered", options={"expose"=true})
     * @Method("POST")
     *
     * @param Request $request
     * @param Project $project
     *
     * @return JsonResponse
     */
    public function listByPageAction(Request $request, Project $project)
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
     * Displays Media entity.
     *
     * @Route("/{project}/media/{id}/show", name="app_admin_project_media_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Project $project
     * @param Media   $media
     *
     * @return Response
     */
    public function showAction(Project $project, Media $media)
    {
        return $this->render(
            'AppBundle:Admin/Media:show.html.twig',
            [
                'project_id' => $project->getId(),
                'media' => $media,
            ]
        );
    }

    /**
     * Creates a new Media entity.
     *
     * @Route("/{project}/media/create", name="app_admin_project_media_create", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request, Project $project)
    {
        $media = new Media();
        $form = $this->createForm(MediaUploadType::class, $media, ['project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($form->getData());
                $em->flush();

                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set(
                        'success',
                        $this
                            ->get('translator')
                            ->trans('admin.media.upload.success', [], 'admin')
                    )
                ;

                return $this->redirectToRoute('app_admin_project_media_list', ['project' => $project->getId()]);
            } catch (\Exception $ex) {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set(
                        'error',
                        $this
                            ->get('translator')
                            ->trans('admin.media.upload.failed', [], 'admin')
                    )
                ;
            }
        }

        return $this->render(
            'AppBundle:Admin/Media:create.html.twig',
            [
                'project_id' => $project->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit a Media entity.
     *
     * @Route("/{project}/media/{id}/edit", name="app_admin_project_media_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Project $project
     * @param Media   $media
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Project $project, Media $media)
    {
        $form = $this->createForm(MediaUploadType::class, $media, ['project' => $project]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->persist($form->getData());
                $em->flush();

                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set(
                        'success',
                        $this
                            ->get('translator')
                            ->trans('admin.media.edit.success', [], 'admin')
                    )
                ;

                return $this->redirectToRoute('app_admin_project_media_list', ['project' => $project->getId()]);
            } catch (\Exception $ex) {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set(
                        'error',
                        $this
                            ->get('translator')
                            ->trans('admin.media.upload.failed', [], 'admin')
                    )
                ;
            }
        }

        return $this->render(
            'AppBundle:Admin/Media:edit.html.twig',
            [
                'media' => $media,
                'project_id' => $project->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete a specific Media entity.
     *
     * @Route("/{project}/media/{id}/delete", name="app_admin_project_media_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     * @param Project $project
     * @param Media   $media
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Project $project, Media $media)
    {
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

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($message);
        }

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('admin.media.delete.success.general', [], 'admin')
            )
        ;

        return $this->redirectToRoute('app_admin_project_media_list', ['project' => $project->getId()]);
    }

    /**
     * Download media file.
     *
     * @Route("/media/{id}/download", name="app_admin_project_media_download", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Media $media
     *
     * @return RedirectResponse|JsonResponse
     */
    public function downloadAction(Media $media)
    {
        $fs = $this->get('app.service.filesystem')->createFileSystem($media->getFileSystem());
        $response = new Response();
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$media->getPath());
        $response->setContent($fs->read($media->getPath()));

        return $response;
    }
}
