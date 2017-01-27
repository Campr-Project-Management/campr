<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/user")
 */
class UserController extends ApiController
{
    /**
     * Sync user information from main website.
     *
     * @Route("/sync", name="app_api_user_sync")
     * @Method({"GET"})
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function syncAction()
    {
        if (!($user = $this->getUser())) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('api.general.not_found', [], 'api_responses'),
            ], Response::HTTP_NOT_FOUND);
        }

        $user = $this->get('app.service.user')->syncUser($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->createApiResponse($user);
    }

    /**
     * Retrieve all user teams.
     *
     * @Route("/{id}/teams", name="app_api_user_teams_get")
     * @Method({"GET"})
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function getTeamsAction(User $user)
    {
        return $this->createApiResponse($user->getTeams());
    }
}
