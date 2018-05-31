<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 */
class DashboardController extends Controller
{
    /**
     * The dashboard page where the user's lands whe going first time int admin tool.
     *
     * @Route("", name="app_admin_dashboard", options={"expose"=true})
     * @Secure(roles="ROLE_ADMIN")
     *
     * @return Response
     */
    public function dashboardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $numberOfProjects = $em
            ->getRepository(Project::class)
            ->countTotal()
        ;

        $numberOfTasks = $em
            ->getRepository(WorkPackage::class)
            ->countTotalByTypeProjectAndStatus(WorkPackage::TYPE_TASK)
        ;

        $numberOfUsers = $em
            ->getRepository(User::class)
            ->countTotal()
        ;

        return $this->render(
            'AppBundle:Default:dashboard.html.twig',
            [
                'number_of_projects' => $numberOfProjects,
                'number_of_tasks' => $numberOfTasks,
                'number_of_users' => $numberOfUsers,
            ]
        );
    }
}
