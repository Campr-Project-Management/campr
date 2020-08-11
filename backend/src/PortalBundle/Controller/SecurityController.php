<?php

namespace PortalBundle\Controller;

use AppBundle\Entity\Team;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\TeamInvite;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Webmozart\Assert\Assert;

class SecurityController extends Controller
{
    /**
     * @Route("/sso/{jwt}", name="portal_login", methods={"GET"})
     *
     * @param Request $request
     * @param string  $jwt
     *
     * @return RedirectResponse
     */
    public function loginAction(Request $request, string $jwt): RedirectResponse
    {
        $routeToRedirectTo = 'app_homepage';

        $token = $this->get('app.jwt_parser')->parse($jwt);
        if (!$token->verify($this->get('app.jwt_signer'), $this->getParameter('secret'))) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.invalid', [], 'messages')
            );
        }

        $redis = $this->get('redis.client');
        if ($redis->exists($jwt)) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.used', [], 'messages')
            );
        }
        $redis->setex($jwt, 3600, time());

        $iat = $token->getClaim('iat', 0);
        $iss = $token->getClaim('iss', '');
        $jti = $token->getClaim('jti');

        if ($redis->exists($jti)) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.used', [], 'messages')
            );
        }
        $redis->setex($jti, 3600, time());

        // check the time of the
        if ($iat + $this->container->getParameter('app.token_duration') < time()) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.expired', [], 'messages')
            );
        }

        // check issuer
        $issParts = explode('://', $iss);
        if (2 !== count($issParts) || $issParts[1] !== $this->getParameter('domain')) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.invalid', [], 'messages')
            );
        }

        $userData = (array) $token->getClaim('user');

        if (isset($userData['redirect_to'])) {
            $router = $this->get('router');
            try {
                $route = $router->match($userData['redirect_to']);

                $routeToRedirectTo = $route['_route'];
            } catch (\Exception $e) {
                throw $this->createNotFoundException(
                    $this
                        ->get('translator')
                        ->trans('unknown_route', [], 'messages')
                );
            }
        }

        $user = $this->createOrUpdateUserFromToken($userData);
        $this->get('app.repository.user')->add($user);

        $teamData = (array) $token->getClaim('workspace', []);
        if (!empty($teamData)) {
            $team = $this->createOrUpdateTeamFromToken($teamData);
            $this->get('app.repository.team')->add($team);
        }

        $teamInviteRepository = $this->get('app.repository.team_invite');
        $projectUserRepository = $this->get('app.repository.project_user');

        /** @var TeamInvite[] $teamInvites */
        $teamInvites = $teamInviteRepository->findPendingInvitesForUser($user);

        foreach ($teamInvites as $teamInvite) {
            $teamInvite->setUser($user);
            $teamInvite->setAcceptedAt(new \DateTime());
            $teamInviteRepository->add($teamInvite);

            $projectUser = $projectUserRepository->findOneBy(
                [
                    'user' => $user,
                    'project' => $teamInvite->getProject(),
                ]
            );
            if (!$projectUser) {
                $projectUser = new ProjectUser();
                $projectUser->setUser($user);
                $projectUser->setProject($teamInvite->getProject());
                $projectUserRepository->add($projectUser);
            }
        }

        //      $this->ensureTeamEnabled($user);

        $upt = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($upt);

        // Redirect after login1 and login2 to #... page (task or something etc)
        if (!empty($request->cookies->get('redirectAfterLogin'))) {
            $domain = $this->getParameter('domain');
            $redirectAfterLogin = $request->cookies->get('redirectAfterLogin');
            $redirectAfterLogin = $request->getScheme().'://'.str_replace($domain, $request->getHost(), $redirectAfterLogin);
            $request->cookies->remove('redirectAfterLogin');

            return $this->redirect($redirectAfterLogin);
        } else {
            return $this->redirectToRoute($routeToRedirectTo, ['subdomain' => $request->attributes->get('subdomain')]);
        }
    }

    /**
     * @param array $data
     *
     * @throws \Exception
     *
     * @return User
     */
    private function createOrUpdateUserFromToken(array $data): User
    {
        $repository = $this->get('app.repository.user');
        $user = $repository->findOneBy(['email' => $data['email']]);

        if (!$user) {
            $repository->setRandomApiTokenIfEmailDoesNotMatch($data['email'], $data['apiToken']);
            $repository->setRandomUUIDIfEmailDoesNotMatch($data['email'], $data['uuid']);
        }

        if (!count($data['roles'])) {
            throw $this->createAccessDeniedException();
        }

        if (!$user) {
            $user = new User();
            $user->setEmail($data['email']);
            $user->setActivatedAt(new \DateTime());
            $user->setEnabled(true);
            $user->setSuspended(false);
        }

        $user->setUsername($data['username']);
        $user->setPlainPassword(microtime(true));
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);
        $user->setPhone($data['phone'] ?? null);
        $user->setRoles($user->getRoles() ?: $data['roles']);
        $user->setApiToken($data['apiToken']);
        $user->setWidgetSettings($data['widgetSettings'] ?? []);
        $user->setFacebook($data['facebook'] ?? null);
        $user->setTwitter($data['twitter'] ?? null);
        $user->setInstagram($data['instagram'] ?? null);
        $user->setGplus($data['gplus'] ?? null);
        $user->setLinkedIn($data['linkedIn'] ?? null);
        $user->setMedium($data['medium'] ?? null);
        $user->setLocale($data['locale']);
        $user->setUuid($data['uuid']);
        $user->setAvatarUrl($data['avatarUrl'] ?? null);
        $user->setTheme($data['theme']);

        return $user;
    }

    /**
     * @param array $data
     *
     * @return Team
     */
    private function createOrUpdateTeamFromToken(array $data): Team
    {
        Assert::notEmpty($data, 'No workspace data');

        /** @var Team $team */
        $team = $this->get('app.repository.team')->findOneBy(['slug' => $data['slug']]);
        if (!$team) {
            $team = $this->get('app.repository.team')->findOneBy(['uuid' => $data['uuid']]);
        }

        if (!$team) {
            $team = new Team();
            $team->setUuid($data['uuid']);
            $team->setSlug($data['slug']);
        }

        $team->setName($data['name']);
        $team->setLogoUrl($data['logoUrl'] ?? null);
        $team->setDescription($data['description'] ?? null);

        /** @var User $user */
        $user = $this->get('app.repository.user')->findOneBy(['email' => $data['userUsername']]);
        if ($user) {
            $team->setUser($user);
        }

        return $team;
    }

    /**
     * @param User $user
     */
    private function ensureTeamEnabled(User $user)
    {
        $slug = $this->get('app.team.context')->getCurrentSlug();

        $enabled = $this->get('portal.client.http.workspace')->isEnabled($slug, $user);
        if ($enabled) {
            return;
        }

        throw $this->createNotFoundException(
            $this->get('translator')->trans('site.disabled', [], 'messages')
        );
    }
}
