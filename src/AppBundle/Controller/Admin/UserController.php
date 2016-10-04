<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;

/**
 * @Route("/admin/users")
 */
class UserController extends Controller
{
    /**
     * @Route("/list", name="app_admin_users_list")
     * @Method({"GET"})
     *
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findAll()
        ;

        return $this->render(
            'AppBundle:Admin\User:list.html.twig',
            [
                'users' => $users,
            ]
        );
    }

    public function createAction()
    {
    }

    public function editAction()
    {
    }

    public function deleteAction()
    {
    }
}
