<?php

namespace PortalBundle\Controller\Webhook;

use AppBundle\Entity\Team;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/webhook/workspaces")
 */
class WorkspaceController extends ApiController
{
    /**
     * @Route("/{uuid}/created", name="portal_webhook_workspace_created", methods={"POST"})
     *
     * @param Team $workspace
     *
     * @return JsonResponse
     */
    public function createdAction(Team $team)
    {
        return $this->json([
            'message' => 'Success',
        ]);
    }
}
