<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\SubteamMember;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MainBundle\Controller\API\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\SubteamMember\CreateType as SubteamMemberCreateType;

/**
 * SubteamMember admin controller.
 *
 * @Route("/admin/subteam-member")
 */
class SubteamMemberController extends ApiController
{
    /**
     * Lists all SubteamMember entities.
     *
     * @Route("/list", name="app_admin_subteam_member_list")
     * @Method("GET")
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $subteamMembers = $em
            ->getRepository(SubteamMember::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin/SubteamMember:list.html.twig',
            [
                'subteamMembers' => $subteamMembers,
            ]
        );
    }

    /**
     * Lists all SubteamMember entities filtered and paginated.
     *
     * @Route("/list/filtered", options={"expose"=true}, name="app_admin_subteam_member_list_filtered")
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
        $response = $dataTableService->paginateByColumn(SubteamMember::class, 'username', $requestParams);

        return $this->createApiResponse($response);
    }

    /**
     * Creates a new SubteamMember entity.
     *
     * @Route("/create", name="app_admin_subteam_member_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $subteamMember = new SubteamMember();
        $form = $this->createForm(SubteamMemberCreateType::class, $subteamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteamMember);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam_member.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_member_list');
        }

        return $this->render(
            'AppBundle:Admin/SubteamMember:create.html.twig',
            [
                'subteamMember' => $subteamMember,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing SubteamMember entity.
     *
     * @Route("/{id}/edit", options={"expose"=true}, name="app_admin_subteam_member_edit")
     * @Method({"GET", "POST"})
     *
     * @param Request       $request
     * @param SubteamMember $subteamMember
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, SubteamMember $subteamMember)
    {
        $form = $this->createForm(SubteamMemberCreateType::class, $subteamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($subteamMember);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.subteam_member.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('app_admin_subteam_member_list');
        }

        return $this->render(
            'AppBundle:Admin/SubteamMember:edit.html.twig',
            [
                'subteamMember' => $subteamMember,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a SubteamMember entity.
     *
     * @Route("/{id}/show", options={"expose"=true}, name="app_admin_subteam_member_show")
     * @Method({"GET"})
     *
     * @param SubteamMember $subteamMember
     *
     * @return Response
     */
    public function showAction(SubteamMember $subteamMember)
    {
        return $this->render(
            'AppBundle:Admin/SubteamMember:show.html.twig',
            [
                'subteamMember' => $subteamMember,
            ]
        );
    }

    /**
     * Deletes a SubteamMember entity.
     *
     * @Route("/{id}/delete", options={"expose"=true}, name="app_admin_subteam_member_delete")
     * @Method({"GET"})
     *
     * @param Request       $request
     * @param SubteamMember $subteamMember
     *
     * @return RedirectResponse|JsonResponse
     */
    public function deleteAction(Request $request, SubteamMember $subteamMember)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($subteamMember);
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
                    ->trans('success.subteam_member.delete.from_edit', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('app_admin_subteam_member_list');
    }
}
