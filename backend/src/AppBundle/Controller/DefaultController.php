<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use MainBundle\Security\TeamVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Default application controller.
 */
class DefaultController extends Controller
{
    /**
     * Application homepage.
     *
     * @Route("/", name="app_homepage", options={"expose"=true})
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // @TODO: this explodes, needs to be rewritten
        // $this->denyAccessUnlessGranted(TeamVoter::VIEW);

        $response = $this->render('AppBundle:Default:index.html.twig');
        $routeParams = $request->attributes->get('_route_params');
        if (isset($routeParams['subdomain'])) {
            $userData = $this->renderView(':partials:user_data.html.twig', [
                'user' => [
                    'api_token' => $this->getUser()->getApiToken(),
                    'locale' => $this->getUser()->getLocale(),
                ],
            ]);
            $content = $response->getContent();
            $content = str_replace(
                [
                    '/static/js/fos_js_routes.js',
                    '<head>',
                ],
                [
                    '/static/js/fos_js_routes_'.$routeParams['subdomain'].'.js',
                    '<head>'.$userData,
                ],
                $content
            );
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * @Route("/login", name="app_default_login")
     */
    public function loginAction(Request $request)
    {
        // TODO: move this into a request listener
        $enabled = $this->get('app.service.team')->isEnabled($request->attributes->get('subdomain'));
        if (!$enabled) {
            throw $this->createNotFoundException(
                $this
                ->get('translator')
                ->trans('site.disabled', [], 'messages')
            );
        }

        $jwt = $request->get('jwt');
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
        if (count($issParts) !== 2 || $issParts[1] !== $this->getParameter('domain')) {
            throw $this->createNotFoundException(
                $this
                    ->get('translator')
                    ->trans('token.invalid', [], 'messages')
            );
        }

        $userData = (array) $token->getClaim('user');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findOneBy([
            'email' => $userData['email'],
        ]);

        if (!$user) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setActivatedAt(new \DateTime());
            $user->setIsEnabled(true);
            $user->setIsSuspended(false);
        }
        $user->setUsername($userData['username']);
        $user->setPlainPassword(microtime(true));
        $user->setFirstName($userData['first_name']);
        $user->setLastName($userData['last_name']);
        $user->setPhone($userData['phone']);
        $user->setRoles($userData['roles']);
        $user->setApiToken($userData['api_token']);
        $user->setWidgetSettings($userData['widget_settings']);
        $user->setAvatar($userData['avatar']);
        $user->setFacebook($userData['facebook']);
        $user->setTwitter($userData['twitter']);
        $user->setInstagram($userData['instagram']);
        $user->setGplus($userData['gplus']);
        $user->setLinkedIn($userData['linked_in']);
        $user->setMedium($userData['medium']);
        $user->setLocale($userData['locale']);

        $em->persist($user);
        $em->flush();

        $upt = new UsernamePasswordToken(
            $user,
            $user->getPassword(),
            'main',
            $user->getRoles()
        );

        $this->get('security.token_storage')->setToken($upt);

        return $this->redirectToRoute('app_homepage', ['subdomain' => $request->attributes->get('subdomain')]);
    }

    /**
     * Logout from team website.
     *
     * @Route("/logout", name="app_logout", options={"expose"=true})
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('main_homepage');
    }
}
