<?php

namespace PortalBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\ProjectUser;
use AppBundle\Entity\TeamInvite;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy(
            [
                'email' => $userData['email'],
            ]
        );

        if (!$user) {
            $em
                ->getRepository(User::class)
                ->setRandomApiTokenIfEmailDoesNotMatch($userData['email'], $userData['apiToken'])
            ;
        }

        if (!count($userData['roles'])) {
            throw $this->createAccessDeniedException();
        }

        if (!$user) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setActivatedAt(new \DateTime());
            $user->setEnabled(true);
            $user->setSuspended(false);
        }

        $user->setUsername($userData['username']);
        $user->setPlainPassword(microtime(true));
        $user->setFirstName($userData['firstName']);
        $user->setLastName($userData['lastName']);
        $user->setPhone($userData['phone'] ?? null);
        $user->setRoles($userData['roles'] ?? null);
        $user->setApiToken($userData['apiToken']);
        $user->setWidgetSettings($userData['widgetSettings'] ?? []);
        $user->setFacebook($userData['facebook'] ?? null);
        $user->setTwitter($userData['twitter'] ?? null);
        $user->setInstagram($userData['instagram'] ?? null);
        $user->setGplus($userData['gplus'] ?? null);
        $user->setLinkedIn($userData['linkedIn'] ?? null);
        $user->setMedium($userData['medium'] ?? null);
        $user->setLocale($userData['locale']);
        $user->setUuid($userData['uuid']);
        $user->setAvatarUrl($userData['avatarUrl'] ?? null);
        $user->setTheme($userData['theme']);

        $em->persist($user);
        $em->flush();

        /** @var TeamInvite[] $teamInvites */
        $teamInvites = $em
            ->getRepository(TeamInvite::class)
            ->findPendingInvitesForUser($user);

        foreach ($teamInvites as $teamInvite) {
            $teamInvite->setUser($user);
            $teamInvite->setAcceptedAt(new \DateTime());
            $em->persist($teamInvite);

            $projectUser = $em
                ->getRepository(ProjectUser::class)
                ->findOneBy(
                    [
                        'user' => $user,
                        'project' => $teamInvite->getProject(),
                    ]
                );
            if (!$projectUser) {
                $projectUser = new ProjectUser();
                $projectUser->setUser($user);
                $projectUser->setProject($teamInvite->getProject());
                $em->persist($projectUser);
            }
            $em->flush();
        }

        $this->ensureTeamEnabled($user);

        $upt = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($upt);

        return $this->redirectToRoute($routeToRedirectTo, ['subdomain' => $request->attributes->get('subdomain')]);
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
