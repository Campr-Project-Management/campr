<?php

namespace PortalBundle\Controller\Api;

use AppBundle\Model\BetaTeam;
use AppBundle\Entity\Team;
use Component\Team\TeamEvents;
use MainBundle\Controller\API\ApiController;
use PortalBundle\Form\Type\TeamType;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/portal/api/teams")
 */
class TeamController extends ApiController
{
    /**
     * @Route("", name="portal_api_team_create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $logger = $this->getLogger();
        $logger->info('Create team request received', ['body' => json_encode($request->request->all())]);

        // softdeleteable is incompatible with UniqueEntity validator
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');

        $team = new Team();
        $form = $this->createForm(TeamType::class, $team, ['csrf_protection' => false, 'allow_extra_fields' => true]);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Team $team */
            $team = $form->getData();

            $this->get('event_dispatcher')->dispatch(TeamEvents::PRE_CREATE, new GenericEvent($team));
            $this->get('app.repository.team')->add($team);
            $this->get('event_dispatcher')->dispatch(TeamEvents::POST_CREATE, new GenericEvent($team));

            $logger->info('Create team request success', ['team' => $team->getSlug()]);

            return $this->createApiResponse($team);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        $logger->error(
            'Create team request is invalid',
            [
                'body' => json_encode($request->request->all()),
                'errors' => json_encode($errors),
            ]
        );

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{uuid}/restore", name="portal_api_team_restore", methods={"POST"})
     *
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function restoreAction(string $uuid): JsonResponse
    {
        $logger = $this->getLogger();
        $logger->info('Restore team request received', ['uuid' => $uuid]);

        $team = $this->get('app.repository.team')->findDeletedByUuid($uuid);
        if (!$team) {
            throw $this->createNotFoundException('Team not found');
        }

        $team->setDeletedAt(null);
        $this->get('event_dispatcher')->dispatch(TeamEvents::PRE_RESTORE, new GenericEvent($team));
        $this->get('app.repository.team')->add($team);
        $this->get('event_dispatcher')->dispatch(TeamEvents::POST_RESTORE, new GenericEvent($team));

        $logger->info('Restore team request success', ['team' => $team->getSlug()]);

        return $this->createApiResponse($team);
    }

    /**
     * @Route("/beta", name="portal_api_beta_team_create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function betaAction(Request $request): JsonResponse
    {
        $logger = $this->getLogger();
        $logger->info('Create beta team request received', ['body' => json_encode($request->request->all())]);

        // softdeleteable is incompatible with UniqueEntity validator
        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');

        $team = new Team();
        $form = $this->createForm(TeamType::class, $team, ['csrf_protection' => false, 'allow_extra_fields' => true]);
        $this->processForm($request, $form);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Team $team */
            $team = $form->getData();
            $betaTeam = new BetaTeam($team, $request->request->get('email'));

            $this->get('event_dispatcher')->dispatch(TeamEvents::PRE_CREATE, new GenericEvent($team));
            $this->get('app.repository.team')->add($team);
            $this->get('event_dispatcher')->dispatch(TeamEvents::BETA_POST_CREATE, new GenericEvent($betaTeam));

            $logger->info('Create team request success', ['team' => $team->getSlug()]);

            return $this->createApiResponse($team);
        }

        $errors = $this->getFormErrors($form);
        $errors = [
            'messages' => $errors,
        ];

        $logger->error(
            'Create beta team request is invalid',
            [
                'body' => json_encode($request->request->all()),
                'errors' => json_encode($errors),
            ]
        );

        return $this->createApiResponse($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{uuid}", name="portal_api_team_delete", methods={"POST"})
     *
     * @param Team $team
     *
     * @return JsonResponse
     */
    public function deleteAction(Team $team): JsonResponse
    {
        $logger = $this->getLogger();

        $logger->info('Delete team request received', ['team' => $team->getSlug()]);

        $this->get('app.repository.team')->remove($team);

        $logger->info('Delete team request success', ['team' => $team->getSlug()]);

        return $this->createApiResponse([], Response::HTTP_OK);
    }

    /**
     * @return LoggerInterface
     */
    private function getLogger(): LoggerInterface
    {
        return $this->get('monolog.logger.portal');
    }
}
