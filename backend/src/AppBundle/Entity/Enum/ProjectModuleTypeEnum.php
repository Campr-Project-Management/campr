<?php

namespace AppBundle\Entity\Enum;

class ProjectModuleTypeEnum
{
    // if you change any of these, remember to update frontend/src/components/_layout/Sidebar.vue
    const CONTRACT = 'contract';
    const ORGANIZATION = 'organization';
    const PHASES_AND_MILESTONES = 'phases_and_milestones';
    const TASK_MANAGEMENT = 'task_management';
    const INTERNAL_COSTS = 'internal_costs';
    const EXTERNAL_COSTS = 'external_costs';
    const RISKS_AND_OPPORTUNITIES = 'risks_and_opportunities';
    const GANTT_CHART = 'gantt_chart';
    const RASCI_MATRIX = 'rasci_matrix';
    const WBS = 'wbs';
    const MEETINGS = 'meetings';
    const TODOS = 'todos';
    const INFOS = 'infos';
    const DECISIONS = 'decisions';
    const STATUS_REPORT = 'status_report';
    const CLOSE_DOWN_PROJECT = 'close_down_project';

    const ELEMENTS = [
        self::CONTRACT => [
            'title' => 'modules.contract.title',
            'description' => 'modules.contract.description',
        ],
        self::ORGANIZATION => [
            'title' => 'modules.organization.title',
            'description' => 'modules.organization.description',
        ],
        self::PHASES_AND_MILESTONES => [
            'title' => 'modules.phases_and_milestones.title',
            'description' => 'modules.phases_and_milestones.description',
        ],
        self::TASK_MANAGEMENT => [
            'title' => 'modules.task_management.title',
            'description' => 'modules.task_management.description',
        ],
        self::INTERNAL_COSTS => [
            'title' => 'modules.internal_costs.title',
            'description' => 'modules.internal_costs.description',
        ],
        self::EXTERNAL_COSTS => [
            'title' => 'modules.external_costs.title',
            'description' => 'modules.external_costs.description',
        ],
        self::RISKS_AND_OPPORTUNITIES => [
            'title' => 'modules.risks_and_opportunities.title',
            'description' => 'modules.risks_and_opportunities.description',
        ],
        self::GANTT_CHART => [
            'title' => 'modules.gantt_chart.title',
            'description' => 'modules.gantt_chart.description',
        ],
        self::RASCI_MATRIX => [
            'title' => 'modules.rasci_matrix.title',
            'description' => 'modules.rasci_matrix.description',
        ],
        self::WBS => [
            'title' => 'modules.wbs.title',
            'description' => 'modules.wbs.description',
        ],
        self::MEETINGS => [
            'title' => 'modules.meetings.title',
            'description' => 'modules.meetings.description',
        ],
        self::TODOS => [
            'title' => 'modules.todos.title',
            'description' => 'modules.todos.description',
        ],
        self::INFOS => [
            'title' => 'modules.infos.title',
            'description' => 'modules.infos.description',
        ],
        self::DECISIONS => [
            'title' => 'modules.decisions.title',
            'description' => 'modules.decisions.description',
        ],
        self::STATUS_REPORT => [
            'title' => 'modules.status_report.title',
            'description' => 'modules.status_report.description',
        ],
        self::CLOSE_DOWN_PROJECT => [
            'title' => 'modules.close_down_project.title',
            'description' => 'modules.close_down_project.description',
        ],
    ];
}
