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

    /** @var ApcuCache */
    private $cacheDriver;

    /** @var FactoryInterface */
    private $factory;

    public function __construct(
        Router $router,
        TokenStorage $securityTokenStorage,
        AuthorizationChecker $securityAuthorizationChecker,
        TranslatorInterface $translator,
        FactoryInterface $factory
    ) {
        $this->router = $router;
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker = $securityAuthorizationChecker;
        $this->translator = $translator;
        $this->metadataReader = new AnnotationDriver(new AnnotationReader());
        $this->cacheDriver = new ApcuCache();
        $this->factory = $factory;
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
            if (php_sapi_name() === 'cli') {
                $route = $this->router->getRouteCollection()->get($routeName);
                $controller = $route->getDefault('_controller');
                list($class, $method) = explode('::', $controller, 2);
                $apcData = ['class' => $class, 'method' => $method];
            } else {
                if (!$this->cacheDriver->contains($routeName)) {
                    $route = $this->router->getRouteCollection()->get($routeName);
                    $controller = $route->getDefault('_controller');
                    list($class, $method) = explode('::', $controller, 2);
                    $this->cacheDriver->save($routeName, ['class' => $class, 'method' => $method]);
                }
                $apcData = $this->cacheDriver->fetch($routeName);
            }
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

    public function createAdminAppMenu()
    {
        $menu = $this
            ->factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'main-menu sidebar-main-menu')
        ;

        $menu
            ->addChild($this->translator->trans('title.dashboard', [], 'messages'), [
                'route' => 'app_admin_dashboard',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-account')
        ;

        $menu
            ->addChild($this->translator->trans('title.user.list', [], 'messages'), [
                'route' => 'app_admin_user_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-account')
        ;

        $menu
            ->addChild($this->translator->trans('title.programme.list', [], 'messages'), [
                'route' => 'app_admin_programme_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-dialpad')
        ;

        $menu
            ->addChild($this->translator->trans('title.portfolio.list', [], 'messages'), [
                'route' => 'app_admin_portfolio_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-laptop-mac')
        ;

        $menu
            ->addChild($this->translator->trans('title.project.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-folder-outline')
            ->addChild($this->translator->trans('title.project.list', [], 'messages'), [
                'route' => 'app_admin_project_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.label.list', [], 'messages'), [
                'route' => 'app_admin_label_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.contract.list', [], 'messages'), [
                'route' => 'app_admin_contract_list',
            ])
            ->getParent()
            ->addChild($this->translator->trans('title.project_objective.list', [], 'messages'), [
                'route' => 'app_admin_project_objective_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_limitation.list', [], 'messages'), [
                'route' => 'app_admin_project_limitation_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_deliverable.list', [], 'messages'), [
                'route' => 'app_admin_project_deliverable_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_complexity.list', [], 'messages'), [
                'route' => 'app_admin_project_complexity_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_category.list', [], 'messages'), [
                'route' => 'app_admin_project_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_cost_type.list', [], 'messages'), [
                'route' => 'app_admin_project_cost_type_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_department.list', [], 'messages'), [
                'route' => 'app_admin_project_department_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_module.list', [], 'messages'), [
                'route' => 'app_admin_project_module_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_role.list', [], 'messages'), [
                'route' => 'app_admin_project_role_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_scope.list', [], 'messages'), [
                'route' => 'app_admin_project_scope_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_status.list', [], 'messages'), [
                'route' => 'app_admin_project_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_team.list', [], 'messages'), [
                'route' => 'app_admin_project_team_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_user.list', [], 'messages'), [
                'route' => 'app_admin_project_user_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.project_work_cost_type.list', [], 'messages'), [
                'route' => 'app_admin_project_work_cost_type_list',
            ])->getParent()
//            ->addChild($this->translator->trans('title.status_report_config.list', [], 'messages'), [
//                'route' => 'app_admin_status_report_config_list',
//            ])->getParent()
            ->addChild($this->translator->trans('title.status_report.list', [], 'messages'), [
                'route' => 'app_admin_status_report_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.workpackage.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-group-work')
            ->addChild($this->translator->trans('title.workpackage.list', [], 'messages'), [
                'route' => 'app_admin_workpackage_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.workpackage_status.list', [], 'messages'), [
                'route' => 'app_admin_workpackage_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.workpackage_category.list', [], 'messages'), [
                'route' => 'app_admin_workpackage_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.wppcwct.list', [], 'messages'), [
                'route' => 'app_admin_wppcwct_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.external_cost.list', [], 'messages'), [
                'route' => 'app_admin_cost_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-money')
        ;

        $menu
            ->addChild($this->translator->trans('title.internal_cost.list', [], 'messages'), [
                'route' => 'app_admin_resource_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-badge-check')
        ;

        $menu
            ->addChild($this->translator->trans('title.subteam.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-folder-outline')
            ->addChild($this->translator->trans('title.subteam.list', [], 'messages'), [
                'route' => 'app_admin_subteam_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.subteam_role.list', [], 'messages'), [
                'route' => 'app_admin_subteam_role_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.subteam_member.list', [], 'messages'), [
                'route' => 'app_admin_subteam_member_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.assignment.list', [], 'messages'), [
                'route' => 'app_admin_assignment_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-assignment')
        ;

        $menu
            ->addChild($this->translator->trans('title.risk_and_opportunities', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-folder-outline')
            ->addChild($this->translator->trans('title.risk.list', [], 'messages'), [
                'route' => 'app_admin_risk_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.risk_category.list', [], 'messages'), [
                'route' => 'app_admin_risk_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.risk_strategy.list', [], 'messages'), [
                'route' => 'app_admin_risk_strategy_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.risk_status.list', [], 'messages'), [
                'route' => 'app_admin_risk_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.opportunity.list', [], 'messages'), [
                'route' => 'app_admin_opportunity_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.opportunity_strategy.list', [], 'messages'), [
                'route' => 'app_admin_opportunity_strategy_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.opportunity_status.list', [], 'messages'), [
                'route' => 'app_admin_opportunity_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.measure.list', [], 'messages'), [
                'route' => 'app_admin_measure_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.meeting.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-accounts')
            ->addChild($this->translator->trans('title.meeting.list', [], 'messages'), [
                'route' => 'app_admin_meeting_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.meeting_category.list', [], 'messages'), [
                'route' => 'app_admin_meeting_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.meeting_objective.list', [], 'messages'), [
                'route' => 'app_admin_meeting_objective_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.meeting_agenda.list', [], 'messages'), [
                'route' => 'app_admin_meeting_agenda_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.meeting_participant.list', [], 'messages'), [
                'route' => 'app_admin_meeting_participant_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.distribution_list.list', [], 'messages'), [
                'route' => 'app_admin_distribution_list_list',
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-arrow-split')
        ;

        $menu
            ->addChild($this->translator->trans('title.info.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-device-hub')
            ->addChild($this->translator->trans('title.info.list', [], 'messages'), [
                'route' => 'app_admin_info_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.info_category.list', [], 'messages'), [
                'route' => 'app_admin_info_category_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.info_status.list', [], 'messages'), [
                'route' => 'app_admin_info_status_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.decision.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-device-hub')
            ->addChild($this->translator->trans('title.decision.list', [], 'messages'), [
                'route' => 'app_admin_decision_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.decision_category.list', [], 'messages'), [
                'route' => 'app_admin_decision_category_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.todo.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-check-all')
            ->addChild($this->translator->trans('title.todo.list', [], 'messages'), [
                'route' => 'app_admin_todo_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.todo_status.list', [], 'messages'), [
                'route' => 'app_admin_todo_status_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.todo_category.list', [], 'messages'), [
                'route' => 'app_admin_todo_category_list',
            ])
        ;

        $menu
            ->addChild($this->translator->trans('title.unit.list', [], 'messages'), [
                'route' => 'app_admin_unit_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-apps')
        ;

        $menu
            ->addChild($this->translator->trans('title.filesystem.list', [], 'messages'), [
                'route' => 'app_admin_filesystem_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-cloud-outline')
        ;

        $menu
            ->addChild($this->translator->trans('title.company.list', [], 'messages'), [
                'route' => 'app_admin_company_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-case')
        ;

        $menu
            ->addChild($this->translator->trans('title.project_close_down.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-case-check')
            ->addChild($this->translator->trans('title.project_close_down.list', [], 'messages'), [
                'route' => 'app_admin_project_close_down_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.evaluation_objective.list', [], 'messages'), [
                'route' => 'app_admin_evaluation_objective_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.lesson.list', [], 'messages'), [
                'route' => 'app_admin_lesson_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.close_down_action.list', [], 'messages'), [
                'route' => 'app_admin_close_down_action_list',
            ])
        ;

        $this->filterMenu($menu);

        return $menu;

        /* @TODO: remove all this when we do the entities cleanup
        $menu
            ->addChild($this->translator->trans('title.calendar.list', [], 'messages'), [])
            ->setAttributes([
                'class' => 'sub-menu main-category',
                'dropdown' => true,
            ])
            ->setLinkAttribute('icon', 'zmdi zmdi-calendar')
            ->addChild($this->translator->trans('title.calendar.list', [], 'messages'), [
                'route' => 'app_admin_calendar_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.day.list', [], 'messages'), [
                'route' => 'app_admin_day_list',
            ])->getParent()
            ->addChild($this->translator->trans('title.working_time.list', [], 'messages'), [
                'route' => 'app_admin_working_time_list',
            ])->getParent()
        ;

        $menu
            ->addChild($this->translator->trans('title.color_status.list', [], 'messages'), [
                'route' => 'app_admin_color_status_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-invert-colors')
            ->getParent()
            ->addChild($this->translator->trans('title.timephase.list', [], 'messages'), [
                'route' => 'app_admin_timephase_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-hourglass')
            ->getParent()
            ->addChild($this->translator->trans('title.schedule.list', [], 'messages'), [
                'route' => 'app_admin_schedule_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-calendar-note')
            ->getParent()
            ->addChild($this->translator->trans('title.impact.list', [], 'messages'), [
                'route' => 'app_admin_impact_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-gps')
            ->getParent()
        ;

        $menu
            ->addChild($this->translator->trans('title.communication.list', [], 'messages'), [
                'route' => 'app_admin_communication_list',
            ])
            ->setAttribute('class', 'main-category')
            ->setLinkAttribute('icon', 'zmdi zmdi-comment')
            ->getParent()
        ;

        // @TODO: Remove this when Notes are completely removed
//        $menu
//            ->addChild($this->translator->trans('title.note.list', [], 'messages'), [])
//            ->setAttributes([
//                'class' => 'sub-menu main-category',
//                'dropdown' => true,
//            ])
//            ->setLinkAttribute('icon', 'zmdi zmdi-comment-edit')
//            ->addChild($this->translator->trans('title.note.list', [], 'messages'), [
//                'route' => 'app_admin_note_list',
//            ])->getParent()
//            ->addChild($this->translator->trans('title.note_status.list', [], 'messages'), [
//                'route' => 'app_admin_note_status_list',
//            ])->getParent()
//        ;


        $this->filterMenu($menu);

        return $menu;*/
    }

    public function createAdminMainMenu()
    {
        $menu = $this
            ->factory
            ->createItem('root')
            ->setChildrenAttribute('class', 'sidebar-menu')
        ;

        $menu
            ->addChild($this->translator->trans('menu.users', [], 'messages'), [
                'route' => 'main_admin_user_list',
            ])
            ->setAttribute('class', 'sidebar-menu-item')
            ->getParent()
            ->addChild($this->translator->trans('menu.teams', [], 'messages'), [
                'route' => 'main_admin_team_list',
            ])
            // ->setAttribute('class', 'sidebar-menu-item')
            // ->getParent()
            // ->addChild($this->translator->trans('menu.payment_methods', [], 'messages'), [
            //     'route' => 'main_admin_payment_method_list',
            // ])
            // ->setAttribute('class', 'sidebar-menu-item')
            // ->getParent()
            // ->addChild($this->translator->trans('menu.payments', [], 'messages'), [
            //     'route' => 'main_admin_payment_list',
            // ])
            ->setAttribute('class', 'sidebar-menu-item')
        ;

        $this->filterMenu($menu);

        return $menu;
    }
}
