<?php

namespace AppBundle\Menu;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\ApcuCache;
use JMS\SecurityExtraBundle\Metadata\ClassMetadata;
use JMS\SecurityExtraBundle\Metadata\Driver\AnnotationDriver;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Translation\TranslatorInterface;

class MenuBuilder
{
    /** @var Router */
    private $router;

    /** @var AnnotationDriver */
    private $metadataReader;

    /** @var TokenStorage */
    private $securityTokenStorage;

    /** @var AuthorizationChecker */
    private $securityAuthorizationChecker;

    /** @var TranslatorInterface */
    private $translator;

    public function __construct(
        Router $router,
        TokenStorage $securityTokenStorage,
        AuthorizationChecker $securityAuthorizationChecker,
        TranslatorInterface $translator
    ) {
        $this->router = $router;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->translator = $translator;
        $this->metadataReader = new AnnotationDriver(new AnnotationReader());
    }

    /**
     * @param $class
     *
     * @return ClassMetadata
     */
    public function getMetadata($class)
    {
        return $this->metadataReader->loadMetadataForClass(new \ReflectionClass($class));
    }

    public function hasRouteAccess($routeName)
    {
        $token = $this->securityTokenStorage->getToken();

        if ($token->isAuthenticated()) {
            $cacheDriver = new ApcuCache();
            if (!$cacheDriver->contains($routeName)) {
                $route = $this->router->getRouteCollection()->get($routeName);
                $controller = $route->getDefault('_controller');
                list($class, $method) = explode('::', $controller, 2);
                $cacheDriver->save($routeName, ['class' => $class, 'method' => $method]);
            }
            $apcData = $cacheDriver->fetch($routeName);
            $metadata = $this->getMetadata($apcData['class']);

            if (!isset($metadata->methodMetadata[$apcData['method']])) {
                return false;
            }

            foreach ($metadata->methodMetadata[$apcData['method']]->roles as $role) {
                if ($this->securityAuthorizationChecker->isGranted($role)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function filterMenu(ItemInterface $menu)
    {
        foreach ($menu->getChildren() as $child) {
            /** @var MenuItem $child */
            $routes = $child->getExtra('routes');

            if ($routes !== null) {
                $route = current(current($routes));

                if ($route && !$this->hasRouteAccess($route)) {
                    $menu->removeChild($child);
                }
            }
            $this->filterMenu($child);
        }

        return $menu;
    }

    public function createAdminAppMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root')->setChildrenAttribute('class', 'main-menu sidebar-main-menu');

        $menu->addChild($this->translator->trans('admin.user.list.title', [], 'admin'), [
                'route' => 'app_admin_user_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-account');

        $menu->addChild($this->translator->trans('admin.project.list.title', [], 'admin'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-folder-outline')
            ->addChild($this->translator->trans('admin.project.list.title', [], 'admin'), [
                'route' => 'app_admin_project_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_category.list.title', [], 'admin'), [
                'route' => 'app_admin_project_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_complexity.list.title', [], 'admin'), [
                'route' => 'app_admin_project_complexity_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_cost_type.list.title', [], 'admin'), [
                'route' => 'app_admin_project_cost_type_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_department.list.title', [], 'admin'), [
                'route' => 'app_admin_project_department_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_module.list.title', [], 'admin'), [
                'route' => 'app_admin_project_module_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_role.list.title', [], 'admin'), [
                'route' => 'app_admin_project_role_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_scope.list.title', [], 'admin'), [
                'route' => 'app_admin_project_scope_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_status.list.title', [], 'admin'), [
                'route' => 'app_admin_project_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_team.list.title', [], 'admin'), [
                'route' => 'app_admin_project_team_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_user.list.title', [], 'admin'), [
                'route' => 'app_admin_project_user_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.project_work_cost_type.list.title', [], 'admin'), [
                'route' => 'app_admin_project_work_cost_type_list',
            ])->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.calendar.list.title', [], 'admin'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-calendar')
            ->addChild($this->translator->trans('admin.calendar.list.title', [], 'admin'), [
                'route' => 'app_admin_calendar_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.day.list.title', [], 'admin'), [
                'route' => 'app_admin_day_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.working_time.list.title', [], 'admin'), [
                'route' => 'app_admin_working_time_list',
            ])->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.portfolio.list.title', [], 'admin'), [
                'route' => 'app_admin_portfolio_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-laptop-mac')
            ->getParent()
            ->addChild($this->translator->trans('admin.color_status.list.title', [], 'admin'), [
                'route' => 'app_admin_color_status_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-invert-colors')
            ->getParent()
            ->addChild($this->translator->trans('admin.workpackage.list.title', [], 'admin'), [
                'route' => 'app_admin_workpackage_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-present-to-all')
            ->getParent()
            ->addChild($this->translator->trans('admin.assignment.list.title', [], 'admin'), [
                'route' => 'app_admin_assignment_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-assignment')
            ->getParent()
            ->addChild($this->translator->trans('admin.timephase.list.title', [], 'admin'), [
                'route' => 'app_admin_timephase_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-hourglass')
            ->getParent()
            ->addChild($this->translator->trans('admin.schedule.list.title', [], 'admin'), [
                'route' => 'app_admin_schedule_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-calendar-note')
            ->getParent()
            ->addChild($this->translator->trans('admin.raci.list.title', [], 'admin'), [
                'route' => 'app_admin_raci_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-view-agenda')
            ->getParent()
            ->addChild($this->translator->trans('admin.wppcwct.list.title', [], 'admin'), [
                'route' => 'app_admin_wppcwct_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-tv-list')
            ->getParent()
            ->addChild($this->translator->trans('admin.unit.list.title', [], 'admin'), [
                'route' => 'app_admin_unit_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-apps')
            ->getParent()
            ->addChild($this->translator->trans('admin.todo.list.title', [], 'admin'), [
                'route' => 'app_admin_todo_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-collection-item')
            ->getParent()
            ->addChild($this->translator->trans('admin.impact.list.title', [], 'admin'), [
                'route' => 'app_admin_impact_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-gps')
            ->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.risk.list.title', [], 'admin'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-folder-outline')
            ->addChild($this->translator->trans('admin.risk.list.title', [], 'admin'), [
                'route' => 'app_admin_risk_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.risk_category.list.title', [], 'admin'), [
                'route' => 'app_admin_risk_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.risk_strategy.list.title', [], 'admin'), [
                'route' => 'app_admin_risk_strategy_list',
            ])->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.status.list.title', [], 'admin'), [
                'route' => 'app_admin_status_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-power-input')
            ->getParent()
            ->addChild($this->translator->trans('admin.communication.list.title', [], 'admin'), [
                'route' => 'app_admin_communication_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-comment')
            ->getParent()
            ->addChild($this->translator->trans('admin.filesystem.list.title', [], 'admin'), [
                'route' => 'app_admin_filesystem_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-cloud-outline')
            ->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.meeting.list.title', [], 'admin'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-accounts')
            ->addChild($this->translator->trans('admin.meeting.list.title', [], 'admin'), [
                'route' => 'app_admin_meeting_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.meeting_agenda.list.title', [], 'admin'), [
                'route' => 'app_admin_meeting_agenda_list',
            ])->getParent()
            ->addChild($this->translator->trans('admin.meeting_participant.list.title', [], 'admin'), [
                'route' => 'app_admin_meeting_participant_list',
            ])->getParent()
        ;

        $menu->addChild($this->translator->trans('admin.note.list.title', [], 'admin'), [
                'route' => 'app_admin_note_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-comment-edit')
            ->getParent()
            ->addChild($this->translator->trans('admin.company.list.title', [], 'admin'), [
                'route' => 'app_admin_company_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-case')
            ->getParent()
            ->addChild($this->translator->trans('admin.decision.list.title', [], 'admin'), [
                'route' => 'app_admin_decision_list',
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-device-hub')
            ->getParent()
        ;

        $this->filterMenu($menu);

        return $menu;
    }

    public function createAdminMainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root')->setChildrenAttribute('class', ' list-unstyled');

        $menu
            ->addChild($this->translator->trans('admin.menu.users', [], 'admin'), [
                'route' => 'main_admin_user_list',
            ])
            ->setAttribute('class', 'list-group-item')
            ->setLinkAttribute('icon', 'glyphicon glyphicon-user')
            ->getParent()
            ->addChild($this->translator->trans('admin.menu.teams', [], 'admin'), [
                'route' => 'main_admin_team_list',
            ])
            ->setAttribute('class', 'list-group-item')
            ->setLinkAttribute('icon', 'glyphicon glyphicon-align-justify')
            ->getParent()
            ->addChild($this->translator->trans('admin.menu.payment_methods', [], 'admin'), [
                'route' => 'main_admin_payment_method_list',
            ])
            ->setAttribute('class', 'list-group-item')
            ->setLinkAttribute('icon', 'glyphicon glyphicon-cog')
            ->getParent()
            ->addChild($this->translator->trans('admin.menu.payments', [], 'admin'), [
                'route' => 'main_admin_payment_list',
            ])
            ->setAttribute('class', 'list-group-item')
            ->setLinkAttribute('icon', 'glyphicon glyphicon-euro')
        ;

        $this->filterMenu($menu);

        return $menu;
    }
}
