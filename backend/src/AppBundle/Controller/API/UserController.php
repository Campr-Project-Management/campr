<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use MainBundle\Controller\API\ApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/users")
 */
class UserController extends ApiController
{
    /**
     * @Route("", name="app_api_users", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $request->query->remove('limit');
        $request->query->remove('offset');

        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findBy($request->query->all(), [], $limit, $offset)
        ;

        return $this->createApiResponse($users);
    }

    /**
     * @Route("/me", name="app_api_users_me", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function meAction()
    {
        return $this->createApiResponse($this->getUser());
    }

    /**
     * Sync user information from main website.
     *
     * @Route("/sync", name="app_api_users_sync", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function syncAction()
    {
        if (!($user = $this->getUser())) {
            return $this->createApiResponse([
                'message' => $this
                    ->get('translator')
                    ->trans('not_found.general', [], 'messages'),
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
     * @Route("/{id}/teams", name="app_api_users_teams_get")
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
