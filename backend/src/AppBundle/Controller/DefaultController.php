<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Exception\NotImplementedException;

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
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        // @TODO: this explodes, needs to be rewritten
        // $this->denyAccessUnlessGranted(TeamVoter::VIEW);

        $theme = $this->getUser()->getTheme();
        $response = $this->render(sprintf('AppBundle:Default:index-%s.html.twig', $theme));
        $routeParams = $request->attributes->get('_route_params');
        if (isset($routeParams['subdomain'])) {
            $userData = $this->renderView(':partials:user_data.html.twig', [
                'user' => [
                    'api_token' => $this->getUser()->getApiToken(),
                    'locale' => $this->getUser()->getLocale(),
                    'theme' => $theme,
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
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function loginAction(Request $request): RedirectResponse
    {
        throw new NotImplementedException('Yet');
    }

    /**
     * Logout from team website.
     *
     * @Route("/logout", name="app_logout", options={"expose"=true})
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function logoutAction(Request $request)
    {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();

        return $this->redirectToRoute('main_homepage');
    }
}
