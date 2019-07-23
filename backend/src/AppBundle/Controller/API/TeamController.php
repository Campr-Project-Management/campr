<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Team;
use Component\Team\TeamEvents;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/team")
 */
class TeamController extends ApiController
{
    /**
     * @Route("", name="app_api_team_show", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        $team = $this->get('app.team.context')->getCurrent();
        if (!$team) {
            throw $this->createNotFoundException();
        }

        return $this->createApiResponse($team);
    }

    /**
     * @Route("/sync", name="app_api_team_sync", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function syncAction(): JsonResponse
    {
        $repository = $this->get('app.repository.team');
        $context = $this->get('app.team.context');
        $slug = $context->getCurrentSlug();
        $team = $context->getCurrent();
        $found = (bool) $team;
        if (!$team) {
            $team = new Team();
            $team->setSlug($slug);
        }

        try {
            $this
                ->get('event_dispatcher')
                ->dispatch(TeamEvents::SYNC, new GenericEvent($team));

            if (!$found) {
                /** @var Team $existingTeam */
                $existingTeam = $repository->findOneBy(['uuid' => $team->getUuid()]);
                if ($existingTeam) {
                    $existingTeam->setSlug($team->getSlug());
                    $existingTeam->setName($team->getName());
                    $existingTeam->setLogoUrl($team->getLogoUrl());
                    $existingTeam->setLogo($team->getLogo());
                    $existingTeam->setDescription($team->getDescription());
                    $team = $existingTeam;
                }
            }

            $repository->add($team);
        } catch (\Exception $e) {
            $this
                ->get('logger')
                ->error($e->getMessage(), ['slug' => $slug, 'trace' => $e->getTraceAsString()]);
            throw $e;
        }

        return $this->createApiResponse($team);
    }
}
