<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\User\CreateType;

/**
 * @Route("/api/team-members")
 */
class TeamMemberController extends ApiController
{
    /**
     * Create new team member.
     *
     * @Route(name="main_api_team_members_create")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request)
    {
        $data = $request->request->all();
        $user = new User();

        $form = $this->createForm(CreateType::class, $user, ['csrf_protection' => false]);
        $this->processForm($request, $form);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $activationToken = $this->get('app.service.user')->generateActivationResetToken();

            $user
                ->setActivationToken($activationToken)
                ->setActivationTokenCreatedAt(new \DateTime())
                ->setPassword($data['password'])
                ->setSalt($data['salt'])
                ->setFacebook(isset($data['facebook']) ? $data['facebook'] : null)
                ->setTwitter(isset($data['twitter']) ? $data['twitter'] : null)
                ->setLinkedIn(isset($data['linkedIn']) ? $data['linkedIn'] : null)
                ->setGplus(isset($data['gplus']) ? $data['gplus'] : null)
            ;
            $team = $em
                ->getRepository(Team::class)
                ->findOneBy([
                    'slug' => $data['teamSlug'],
                ])
            ;
            $teamMember = (new TeamMember())
                ->setUser($user)
                ->setTeam($team)
                ->setRoles([User::ROLE_USER])
            ;

            $em->persist($user);
            $em->persist($teamMember);
            $em->flush();

            $mailerService = $this->get('app.service.mailer');
            $mailerService->sendEmail(
                'MainBundle:Email:create_team_member.html.twig',
                'info',
                $user->getEmail(),
                [
                    'token' => $user->getActivationToken(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'password' => $user->getPassword(),
                    'expiration_time' => $this->getParameter('activation_token_expiration_number'),
                ]
            );

            return $this->createApiResponse($user, Response::HTTP_CREATED);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }
}
