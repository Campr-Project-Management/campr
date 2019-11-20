<?php

namespace PortalBundle\Controller\Api;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/workspaces")
 */
class WorkspaceController extends ApiController
{
    /**
     * @Route("/{slug}/check-enabled", name="portal_api_workspace_activate", methods={"GET"})
     *
     * @param Team $team
     *
     * @return JsonResponse
     */
    public function checkEnabledAction(Team $team)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$team->userIsMember($user) && $team->getUser() !== $user) {
            $this->denyAccessUnlessGranted([User::ROLE_ADMIN]);
        }

        return $this->json([
            'enabled' => $team->isEnabled(),
        ]);
    }

    /**
     * @Route("/{slug}", name="portal_api_workspace_show", methods={"GET"})
     *
     * @param Team $team
     *
     * @return JsonResponse
     */
    public function showAction(Team $team)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$team->userIsMember($user) && $team->getUser() !== $user) {
            $this->denyAccessUnlessGranted([User::ROLE_ADMIN]);
        }

        return $this->json($team);
    }
}
