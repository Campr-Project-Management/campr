<?php

namespace MainBundle\Controller\Admin;

use AppBundle\Entity\TeamInvite;
use JMS\SecurityExtraBundle\Annotation\Secure;
use MainBundle\Form\Team\TeamInvitesForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/team-invites")
 */
class TeamInviteController extends Controller
{
    /**
     * @Route("/unaccepted", name="main_admin_team_invite_unaccepted")
     * @Method({"GET", "POST"})
     * @Secure(roles={"ROLE_ADMIN", "ROLE_SUPER_ADMIN"})
     */
    public function unacceptedAction(Request $request)
    {
        $teamInvites = $this
            ->getDoctrine()
            ->getRepository(TeamInvite::class)
            ->findUnaccepted()
        ;
        $form = $this->createForm(TeamInvitesForm::class, [], [
            'method' => Request::METHOD_POST,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('resend' === $form->get('action')->getData()) {
                $this->handleResend($form);
            }

            if ('delete' === $form->get('action')->getData()) {
                $this->handleDelete($form);
            }

            return $this->redirectToRoute('main_admin_team_invite_unaccepted');
        }

        return $this->render(
            'MainBundle:Admin/TeamInvite:list.html.twig',
            [
                'team_invites' => $teamInvites,
                'form' => $form->createView(),
            ]
        );
    }

    private function handleResend(FormInterface $form)
    {
        $mailerService = $this->get('app.service.mailer');
        /** @var TeamInvite[] $teamInvites */
        $teamInvites = $form->get('teamInvites')->getData();
        $em = $this->getDoctrine()->getEntityManager();
        $removedInvalid = 0;
        $resent = 0;

        foreach ($teamInvites as $teamInvite) {
            $email = $teamInvite->getUser() ? $teamInvite->getUser()->getEmail() : $teamInvite->getEmail();

            try {
                $mailerService->sendEmail(
                    'MainBundle:Email:invite_user.html.twig',
                    'default',
                    $email,
                    [
                        'token' => $teamInvite->getToken(),
                        'user_email' => $email,
                        'team' => $teamInvite->getTeam(),
                    ]
                );
                ++$resent;
            } catch (\Exception $e) {
                // automatically remove invalid email/emails that cannot be emailed to
                $em->remove($teamInvite);

                ++$removedInvalid;
            }
        }

        $em->flush();

        $trans = $this->get('translator');

        if ($removedInvalid) {
            $this->addFlash(
                'success',
                $trans->trans(
                    'message.removed_invalid_team_invites',
                    ['%count%' => $removedInvalid]
                )
            );
        }

        if ($resent) {
            $this->addFlash(
                'success',
                $trans->trans(
                    'message.resent_team_invites',
                    ['%count%' => $resent]
                )
            );
        }
    }

    private function handleDelete(FormInterface $form)
    {
        $teamInvites = $form->get('teamInvites')->getData();
        $em = $this->getDoctrine()->getEntityManager();
        $removed = 0;

        foreach ($teamInvites as $teamInvite) {
            $em->remove($teamInvite);
            ++$removed;
        }

        $em->flush();

        $trans = $this->get('translator');
        $this->addFlash(
            'success',
            $trans->trans(
                'message.removed_team_invites',
                ['%count%' => $removed]
            )
        );
    }
}
