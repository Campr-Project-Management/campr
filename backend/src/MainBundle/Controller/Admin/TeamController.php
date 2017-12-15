<?php

namespace MainBundle\Controller\Admin;

use AppBundle\Entity\Team;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Form\Team\CreateType;
use MainBundle\Form\Team\EditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Team admin controller.
 *
 * @Route("/admin/team")
 */
class TeamController extends Controller
{
    /**
     * List all Team entities.
     *
     * @Route("/list", name="main_admin_team_list")
     * @Method({"GET"})
     * @Secure(roles={"ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     *
     * @return Response
     */
    public function listAction()
    {
        $teams = $this
            ->getDoctrine()
            ->getRepository(Team::class)
            ->findAll()
        ;

        return $this->render(
            'MainBundle:Admin/Team:list.html.twig',
            [
                'teams' => $teams,
            ]
        );
    }

    /**
     * Creates a new Team entity.
     *
     * @Route("/create", name="main_admin_team_create")
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
            $team = $form->getData();
            $em->persist($team);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workspace.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_admin_team_list');
        }

        return $this->render(
            'MainBundle:Admin/Team:create.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing Team entity.
     *
     * @Route("/{id}/edit", name="main_admin_team_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Team    $team
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($team);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.workspace.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_admin_team_list');
        }

        return $this->render(
            'MainBundle:Admin/Team:edit.html.twig',
            [
                'team' => $team,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific Team entity.
     *
     * @Route("/{id}/delete", name="main_admin_team_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Team $team
     *
     * @return Response
     */
    public function deleteAction(Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.workspace.delete', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('main_admin_team_list');
    }
}
