<?php

namespace MainBundle\Controller;

use AppBundle\Entity\User;
use Component\Locale\Context\LocaleContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/locale")
 */
class LocaleController extends Controller
{
    const ONE_YEAR = 60 * 60 * 24 * 365;

    /**
     * @param string|null $localeCode
     * @param Request     $request
     *
     * @return RedirectResponse
     *
     * @Route("/switch/{localeCode}", name="main_locale_switch", methods={"GET"})
     */
    public function switchAction(Request $request, string $localeCode = null): RedirectResponse
    {
        if (empty($localeCode)) {
            $localeCode = $this->getDefaultLocaleCode();
        }

        $targetUrl = urldecode($request->get('targetUrl', $this->generateUrl('main_homepage')));

        if (!$this->isValidLocaleCode($localeCode)) {
            $localeCode = $this->getDefaultLocaleCode();
        }

        $this->setUserLocale($localeCode);

        $response = $this->redirect($targetUrl);
        $response->headers->setCookie(
            new Cookie(LocaleContextInterface::STORAGE_KEY, $localeCode, time() + LocaleContextInterface::TTL)
        );

        return $response;
    }

    /**
     * @param string $localeCode
     *
     * @return bool
     */
    protected function setUserLocale(string $localeCode): bool
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!($user instanceof User)) {
            return false;
        }

        $user->setLocale($localeCode);

        $this
            ->get('app.repository.user')
            ->add($user)
        ;

        return true;
    }

    /**
     * @return string
     */
    private function getDefaultLocaleCode(): string
    {
        return $this->get('app.locale.provider')->getDefaultLocaleCode();
    }

    /**
     * @param string $localeCode
     *
     * @return bool
     */
    private function isValidLocaleCode(string $localeCode): bool
    {
        return in_array($localeCode, $this->get('app.locale.provider')->getAvailableLocalesCodes());
    }
}
