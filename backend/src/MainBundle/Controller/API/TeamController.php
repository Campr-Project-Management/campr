<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/teams")
 */
class TeamController extends ApiController
{
    /**
     * Retrieve Team information.
     *
     * @Route("/{id}", name="main_api_team_get")
     * @Method({"GET"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function getAction($id)
    {
        $team = $this
            ->getDoctrine()
            ->getRepository(Team::class)
            ->findOneByIDOrSlug($id)
        ;

        if (!$team) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createApiResponse($team);
    }

    /**
     * @Route("/{id}/enabled", name="main_api_team_enabled")
     * @Method({"GET"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function enabledAction($id)
    {
        $team = $this
            ->getDoctrine()
            ->getRepository(Team::class)
            ->findOneByIDOrSlug($id)
        ;

        if (!$team) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->createApiResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
            'slug' => $team->getSlug(),
            'enabled' => $team->isEnabled(),
            'available' => $team->isAvailable(),
        ]);
    }
}
