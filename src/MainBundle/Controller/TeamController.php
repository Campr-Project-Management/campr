<?php

namespace MainBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Team controller.
 *
 * @Route("/team")
 */
class TeamController extends Controller
{
    /**
     * Team list.
     *
     * @Route("/list", name="main_team_list")
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->render('MainBundle:Team:list.html.twig');
    }

    /**
     * Create new team.
     *
     * @Route("/create", name="main_team_create")
     *
     * @return Response
     */
    public function createAction()
    {
        return $this->render('MainBundle:Team:create.html.twig');
    }

    /**
     * Edit a specific team.
     *
     * @Route("/{id}/edit", name="main_team_edit")
     *
     * @return Response
     */
    public function editAction($id)
    {
        return $this->render(
            'MainBundle:Team:edit.html.twig',
            [
                'team' => $id,
            ]
        );
    }
}
