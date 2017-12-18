<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamMember;
use AppBundle\Entity\User;
use AppBundle\Form\User\ApiCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $user = new User();
        $form = $this->createForm(ApiCreateType::class, $user, ['csrf_protection' => false]);
        $this->processForm($request, $form);
        if ($form->isValid()) {
            $translator = $this->get('translator');
            $em = $this->getDoctrine()->getManager();
            $existingUser = $em->getRepository(User::class)->findOneBy([
                'email' => $user->getEmail(),
            ]);
            $subDomain = $form->get('subdomain')->getData();
            $team = $em->getRepository(Team::class)->findOneBySlug($subDomain);
            if (!$team) {
                return $this->createApiResponse(
                    [
                        'messages' => [
                            $translator->trans('not_found.team', [], 'messages'),
                        ],
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            // @TODO: implement notification to send to new users to activate
            // @TODO: implement notification to existing users that they were added to a team
            if ($existingUser) {
                if (!$existingUser->hasTeam($team)) {
                    $teamMember = new TeamMember();
                    $teamMember->setTeam($team);
                    $teamMember->setRoles($user->getRoles());
                    $existingUser->addTeamMember($teamMember);
                    $em->persist($existingUser);
                    $em->flush();
                    $user = $existingUser;

                    $mailerService = $this->get('app.service.mailer');
                    $mailerService->sendEmail(
                        'MainBundle:Email:create_team_member.html.twig',
                        'info',
                        $user->getEmail(),
                        [
                            'token' => $user->getActivationToken(),
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'password' => $user->getPlainPassword(),
                            'expiration_time' => $this->getParameter('activation_token_expiration_number'),
                        ]
                    );
                } else {
                    return $this->createApiResponse(
                        [
                            'messages' => [
                                $translator->trans('exception.user.already_member_of_workspace', [], 'messages'),
                            ],
                        ],
                        Response::HTTP_BAD_REQUEST
                    );
                }
            } else {
                $teamMember = new TeamMember();
                $user->setRoles([
                    User::ROLE_USER,
                ]);
                $teamMember->setTeam($team);
                $teamMember->setRoles($user->getRoles());
                $user->addTeamMember($teamMember);
                $em->persist($user);
                $em->flush();

                $this
                    ->get('app.service.mailer')
                    ->sentRegistrationEmail($user)
                ;
            }

            return $this->createApiResponse($user);
        }

        return $this->createApiResponse(
            [
                'messages' => $this->getFormErrors($form),
            ],
            Response::HTTP_BAD_REQUEST
        );
    }
}
