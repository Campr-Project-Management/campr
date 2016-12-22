<?php

namespace MainBundle\Controller;

use AppBundle\Command\RedisQueueManagerCommand;
use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\Criteria;
use MainBundle\Form\TeamType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
    public function createAction(Request $request)
    {
        $form = $this->createForm(TeamType::class);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $team = $form->getData();
            $team->setUser($this->getUser());
            $teamMember = new TeamMember();
            $teamMember->setTeam($team);
            $teamMember->setUser($this->getUser());
            $teamMember->setRoles([User::ROLE_SUPER_ADMIN]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->persist($teamMember);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Team created!');

            $env = $this->getParameter('kernel.environment');
            $redis = $this->get('redis.client');
            $redis->rpush(RedisQueueManagerCommand::DEFAULT, [
                sprintf(
                    '--env=%s_%s doctrine:database:create -n',
                    str_replace('-', '_', $team->getSlug()),
                    $env
                ),
            ]);
            $redis->rpush(RedisQueueManagerCommand::DEFAULT, [
                sprintf(
                    '--env=%s_%s doctrine:migrations:migrate -n',
                    str_replace('-', '_', $team->getSlug()),
                    $env
                ),
            ]);

            return $this->redirectToRoute('main_team_edit', ['id' => $team->getId()]);
        }

        return $this->render('MainBundle:Team:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit a specific team.
     *
     * @Route("/{id}/edit", name="main_team_edit")
     *
     * @return Response
     */
    public function editAction(Request $request, Team $team)
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $team = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Team saved!');

            return $this->redirectToRoute('main_team_edit', ['id' => $team->getId()]);
        }

        return $this->render(
            'MainBundle:Team:edit.html.twig',
            [
                'team' => $team,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/sso", name="main_team_sso")
     */
    public function ssoAction(Request $request, Team $team)
    {
        if (!$team->isEnabled()) {
            throw $this->createNotFoundException('Team is disabled.');
        }

        /** @var User $user */
        $user = $this->getUser();
        $subdomain = sprintf('%s://%s.%s', $request->getScheme(), $team->getSlug(), $request->getHost());

        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('user', $user));
        $teamMember = $team->getTeamMembers()->matching($criteria)->first();
        $roles = $teamMember
            ? $teamMember->getRoles()
            // if the team member entry is not found return super admin if team belongs to user or user otherwise
            : ($team->getUser() === $user ? [User::ROLE_SUPER_ADMIN] : [User::ROLE_USER])
        ;

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'phone' => $user->getPhone(),
            'roles' => $roles,
        ];

        $signer = $this->get('app.jwt_signer');
        $token = $this->get('app.jwt_builder')
            ->setIssuer(sprintf('%s://%s', $request->getScheme(), $request->getHost()))
            ->setIssuedAt(time())
            ->setExpiration(time() + 3)
            ->setId(uniqid())
            ->set('user', $userData)
            ->sign($signer, $this->getParameter('secret'))
            ->getToken()
        ;

        return $this->redirect($subdomain.'/login?'.http_build_query(['jwt' => (string) $token]));
    }
}
