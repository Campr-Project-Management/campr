<?php

namespace MainBundle\Controller\Admin;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use MainBundle\Form\TeamMember\CreateType;
use MainBundle\Form\TeamMember\EditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TeamMember admin controller.
 *
 * @Route("/admin/team")
 */
class TeamMemberController extends Controller
{
    /**
     * List all TeamMember entities.
     *
     * @Route("/{team}/member/list", name="main_admin_team_member_list")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function listAction(Team $team)
    {
        $teamMembers = $this
            ->getDoctrine()
            ->getRepository(TeamMember::class)
            ->findBy(['team' => $team])
        ;

        return $this->render(
            'MainBundle:Admin/TeamMember:list.html.twig',
            [
                'team' => $team,
                'team_members' => $teamMembers,
            ]
        );
    }

    /**
     * Creates a new TeamMember entity.
     *
     * @Route("/{team}/member/create", name="main_admin_team_member_create")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Team    $team
     *
     * @return Response|RedirectResponse
     */
    public function createAction(Request $request, Team $team)
    {
        $em = $this->getDoctrine()->getManager();
        $teamMember = new TeamMember();
        $teamMember->setTeam($team);

        $form = $this->createForm(CreateType::class, $teamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teamMember = $form->getData();
            $em->persist($teamMember);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.team_member.create', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_admin_team_member_list', ['team' => $team->getId()]);
        }

        return $this->render(
            'MainBundle:Admin/TeamMember:create.html.twig',
            [
                'team' => $team,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Displays a form to edit an existing TeamMember entity.
     *
     * @Route("/{team}/member/{id}/edit", name="main_admin_team_member_edit", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     * @param Request    $request
     * @param Team       $team
     * @param TeamMember $teamMember
     *
     * @return Response|RedirectResponse
     */
    public function editAction(Request $request, Team $team, TeamMember $teamMember)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EditType::class, $teamMember);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($teamMember);
            $em->flush();

            $this
                ->get('session')
                ->getFlashBag()
                ->set(
                    'success',
                    $this
                        ->get('translator')
                        ->trans('success.team_member.edit', [], 'flashes')
                )
            ;

            return $this->redirectToRoute('main_admin_team_member_list', ['team' => $team->getId()]);
        }

        return $this->render(
            'MainBundle:Admin/TeamMember:edit.html.twig',
            [
                'team_member' => $teamMember,
                'team' => $team,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Deletes a specific TeamMember entity.
     *
     * @Route("/{team}/member/{id}/delete", name="main_admin_team_member_delete", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Team $team
     *
     * @return Response
     */
    public function deleteAction(Team $team, TeamMember $teamMember)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($teamMember);
        $em->flush();

        $this
            ->get('session')
            ->getFlashBag()
            ->set(
                'success',
                $this
                    ->get('translator')
                    ->trans('success.team_member.delete', [], 'flashes')
            )
        ;

        return $this->redirectToRoute('main_admin_team_member_list', ['team' => $team->getId()]);
    }
}
