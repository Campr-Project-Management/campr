<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use AppBundle\Entity\TeamInvite;
use AppBundle\Entity\User;
use MainBundle\Form\InviteUserType;
use MainBundle\Security\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/workspaces")
 */
class WorkspaceController extends ApiController
{
    /**
     * @Route("/{workspace}/invite-member", name="main_api_workspace_invite_member", options={"expose"=true})
     * @ParamConverter("workspace", class="AppBundle:Team", options={"repository_method"="findOneByIDOrSlug"})
     * @Method({"POST"})
     */
    public function inviteMember(Request $request, Team $workspace)
    {
        $this->denyAccessUnlessGranted(TeamVoter::INVITE, $workspace);

        $form = $this->createForm(
            InviteUserType::class,
            null,
            [
                'team' => $workspace,
                'user' => $this->getUser(),
                'csrf_protection' => false,
            ]
        );
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em
                ->getRepository(User::class)
                ->findOneBy([
                    'email' => $email,
                ])
            ;

            // check for already existing invite
            $teamInvite = $em
                ->getRepository(TeamInvite::class)
                ->findOneByEmailAndTeam($email, $workspace)
            ;

            if (!$teamInvite) {
                $teamInvite = new TeamInvite();
                $teamInvite->setTeam($workspace);
            }

            // if there's a team invite by email but the user registered in the mean time
            // this will associate the invite with the existing user
            if ($user) {
                $teamInvite->setUser($user);
            } else {
                $teamInvite->setEmail($email);
            }

            $token = uniqid(rand(1000, 9999), true);
            $teamInvite->setToken($token);
            $teamInvite->setCreatedAt(new \DateTime());

            $em->persist($teamInvite);
            $em->flush();

            $mailerService = $this->get('app.service.mailer');
            $mailerService->sendEmail(
                'MainBundle:Email:invite_user.html.twig',
                'info',
                $email,
                [
                    'token' => $token,
                    'user_email' => $email,
                    'team' => $workspace,
                ]
            );

            $message = $this
                ->get('translator')
                ->trans('success.workspace_member.invite', ['%user_email%' => $email], 'flashes')
            ;

            return $this->createApiResponse([
                'messages' => [$message],
            ]);
        }

        return $this->createApiResponse([
            'errors' => $this->getFormErrors($form),
        ]);
    }
}
