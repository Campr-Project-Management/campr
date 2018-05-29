<?php

namespace AppBundle\Controller\Admin;

use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Resource;
use AppBundle\Form\Resource\CreateType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resource admin controller.
 *
 * @Route("/admin/resource")
 */
class ResourceController extends BaseController
{
    /**
     * List all Resource entities.
     *
     * @Route("/list", name="app_admin_resource_list")
     * @Method({"GET"})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $resources = $this
            ->getDoctrine()
            ->getRepository(Resource::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/Resource:list.html.twig',
            [
                'resources' => $resources,
            ]
        );
    }

    /**
     * Lists all Resource entities filtered and paginated.
     *
     * @Route("/list/filtered", name="app_admin_resource_list_filtered", options={"expose"=true})
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
        $response = $dataTableService->paginateByColumn(Resource::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Displays Resource entity.
     *
     * @Route("/{id}/show", name="app_admin_resource_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param resource $resource
     *
     * @return Response
     */
    public function showAction(Resource $resource)
    {
        return $this->render(
            'AppBundle:Admin/Resource:show.html.twig',
            [
                'resource' => $resource,
            ]
        );
    }

    /**
     * Creates a new Resource entity.
     *
     * @Route("/create", name="app_admin_resource_create")
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
            $this->persistAndFlush($form->getData());
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.resource.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_resource_list');
        }

        return $this->render(
            'AppBundle:Admin/Resource:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Resource entity.
     *
     * @Route("/{id}/edit", name="app_admin_resource_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request  $request
     * @param resource $resource
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Resource $resource)
    {
        $form = $this->createForm(CreateType::class, $resource);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($resource);
            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.resource.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_resource_list');
        }

        return $this->render(
            'AppBundle:Admin/Resource:edit.html.twig',
            [
                'id' => $resource->getId(),
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Resource entity.
     *
     * @Route("/{id}/delete", name="app_admin_resource_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request  $request
     * @param resource $resource
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, Resource $resource)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($resource);
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
                    ->trans('success.resource.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_resource_list');
    }
}
