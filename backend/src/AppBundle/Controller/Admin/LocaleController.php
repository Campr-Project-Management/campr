<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use Component\Locale\LocaleEvents;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use MainBundle\Controller\LocaleController as BaseLocaleController;

/**
 * @Route("/admin/locale")
 */
class LocaleController extends BaseLocaleController
{
    /**
     * @param string|null $localeCode
     * @param Request     $request
     *
     * @return RedirectResponse
     *
     * @Route("/switch/{localeCode}", name="app_admin_locale_switch")
     * @Method("GET")
     */
    public function switchAction(Request $request, string $localeCode = null): RedirectResponse
    {
        return parent::switchAction($request, $localeCode);
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

        try {
            $this
                ->get('event_dispatcher')
                ->dispatch(LocaleEvents::SWITCH, new GenericEvent($localeCode))
            ;
        } catch (\Exception $e) {
            $this
                ->get('logger')
                ->error(
                    sprintf('Error changing master user locale: %s', $e->getMessage()),
                    ['user' => $user->getId()]
                )
            ;
        }

        return true;
    }
}
