<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/team")
 */
class TeamController extends Controller
{
    /**
     * @Route("/{id}", name="main_api_team_get")
     */
    public function getAction($id)
    {
        $team = $this
            ->getDoctrine()
            ->getRepository(Team::class)
            ->findOneByIDOrSlug($id)
        ;

        if (!$team) {
            return new JsonResponse([
                'message' => 'Not found.',
            ], 404);
        }

        return new JsonResponse([
            'id' => $team->getId(),
            'name' => $team->getName(),
            'slug' => $team->getSlug(),
            'enabled' => $team->isEnabled(),
            'description' => $team->getDescription(),
            'created_at' => $team->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
