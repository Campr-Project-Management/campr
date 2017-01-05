<?php

namespace MainBundle\Controller\API;

use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/team")
 */
class TeamController extends Controller
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
            return new JsonResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('api.general.not_found', [], 'api_responses'),
            ], Response::HTTP_NOT_FOUND);
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
