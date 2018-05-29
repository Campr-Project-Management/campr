<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SubteamRole;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\API\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\SubteamRole\CreateType as SubteamRoleCreateType;

/**
 * SubteamRole admin controller.
 *
 * @Route("/admin/subteam-role")
 */
class SubteamRoleController extends ApiController
{
    /**
     * Lists all SubteamRole entities.
     *
     * @Route("/list", name="app_admin_subteam_role_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $subteams = $em
            ->getRepository(SubteamRole::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/SubteamRole:list.html.twig',
            [
                'subteams' => $subteams,
            ]
        );
    }

    /**
     * Lists all SubteamRole entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_subteam_role_list_filtered")
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
        $response = $dataTableService->paginateByColumn(SubteamRole::class, 'name', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new SubteamRole entity.
     *
     * @Route("/create", name="app_admin_subteam_role_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $subteamRole = new SubteamRole();
        $form = $this->createForm(SubteamRoleCreateType::class, $subteamRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteamRole);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam_role.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_role_list');
        }

        return $this->render(
            'AppBundle:Admin/SubteamRole:create.html.twig',
            [
                'subteamRole' => $subteamRole,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing SubteamRole entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_subteam_role_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request     $request
     * @param SubteamRole $subteamRole
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, SubteamRole $subteamRole)
    {
        $form = $this->createForm(SubteamRoleCreateType::class, $subteamRole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteamRole);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam_role.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_role_list');
        }

        return $this->render(
            'AppBundle:Admin/SubteamRole:edit.html.twig',
            [
                'subteamRole' => $subteamRole,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a SubteamRole entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_subteam_role_show")
     * @Method({"GET"})
     *
     * @param SubteamRole $subteamRole
     *
     * @return Response
     */
    public function showAction(SubteamRole $subteamRole)
    {
        return $this->render(
            'AppBundle:Admin/SubteamRole:show.html.twig',
            [
                'subteamRole' => $subteamRole,
            ]
        );
    }

    /**
     * Deletes a SubteamRole entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_subteam_role_delete")
     * @Method({"GET"})
     *
     * @param Request     $request
     * @param SubteamRole $subteamRole
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, SubteamRole $subteamRole)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subteamRole);
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
                    ->trans('success.subteam_role.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_subteam_role_list');
    }
}
