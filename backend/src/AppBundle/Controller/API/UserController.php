<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\User;
use Component\Locale\Context\LocaleContextInterface;
use Component\Locale\LocaleEvents;
use Component\User\UserEvents;
use MainBundle\Controller\API\ApiController;
use MainBundle\Form\User\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/users")
 */
class UserController extends ApiController
{
    const ONE_YEAR = 60 * 60 * 24 * 365;

    /**
     * @Route("", name="app_api_users", options={"expose"=true})
     * @Method({"GET"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAction(Request $request)
    {
        $limit = $request->query->get('limit');
        $offset = $request->query->get('offset');

        $request->query->remove('limit');
        $request->query->remove('offset');

        $criteria = $request->query->all();
        $criteria['deletedAt'] = null;

        $users = $this
            ->get('app.repository.user')
            ->findBy($criteria, [], $limit, $offset)
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
            return $this->createApiResponse(
                [
                    'message' => $this
                        ->get('translator')
                        ->trans('not_found.general', [], 'messages'),
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        try {
            $this
                ->get('event_dispatcher')
                ->dispatch(UserEvents::SYNC, new GenericEvent($user))
            ;

            $this->get('app.repository.user')->add($user);
        } catch (\Exception $e) {
            $this
                ->get('logger')
                ->error(sprintf('Error syncing user: %s', $e->getMessage()), ['user' => $user->getId()])
            ;
        }

        return $this->createApiResponse($user);
    }

    /**
     * @Route("/me", name="app_api_users_me_edit", options={"expose"=true})
     * @Method({"POST", "PATCH"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateMeAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $repository = $this->get('app.repository.user');
        $user = $repository->find($user->getId());
        if (!$user) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(AccountType::class, $user, ['csrf_protection' => false]);

        $this->processForm($request, $form, $request->isMethod('POST'));
        if (!$form->isValid()) {
            return $this->createApiResponse(
                [
                    'messages' => $this->getFormErrors($form),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $repository->add($user);

        return $this->createApiResponse($user);
    }

    /**
     * @Route("/me/locale", name="app_api_switch_locale", options={"expose"=true})
     * @Method({"PATCH"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function switchLocaleAction(Request $request)
    {
        $response = $this->updateMeAction($request);
        /** @var User $user */
        $user = $this->getUser();
        $localeCode = $user->getLocale();

        try {
            $this
                ->get('event_dispatcher')
                ->dispatch(LocaleEvents::SWITCH, new GenericEvent($localeCode))
            ;
        } catch (\Exception $e) {
            $this
                ->get('logger')
                ->error(sprintf('Error updating master user locale: %s', $e->getMessage()), ['user' => $user->getId()])
            ;
        }

        $response->headers->setCookie(
            new Cookie(LocaleContextInterface::STORAGE_KEY, $localeCode, time() + LocaleContextInterface::TTL)
        );

        return $response;
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
