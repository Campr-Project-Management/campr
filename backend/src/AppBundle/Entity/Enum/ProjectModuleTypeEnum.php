<?php

namespace AppBundle\Entity\Enum;

class ProjectModuleTypeEnum
{
    const PROJECT_CONTRACT = 'project_contract';
    const PROJECT_ORGANIZATION = 'project_organization';
    const PLAN = 'plan';
    const TASK_MANAGEMENT = 'task_management';
    const PHASES_MILESTONES = 'phases_milestones';
    const COSTS = 'costs';
    const RESOURCES = 'resources';
    const RISKS_OPPORTUNITIES = 'risks_opportunities';
    const COMMUNICATION = 'communication';
    const CONTROL_MEASURES = 'control_measures';
    const STATUS_REPORT = 'status_report';
    const MEETINGS = 'meetings';
    const TODOS = 'todos';
    const NOTES = 'notes';
    const CLOSE_DOWN_PROJECT = 'close_down_project';
    const RASCI_MATRIX = 'rasci_matrix';
    const TASK_CHART = 'task_chart';
    const GANTT_CHART = 'gantt_chart';
    const DECISIONS = 'decisions';

    const ELEMENTS = [
        self::PROJECT_CONTRACT => [
            'title' => 'modules.project_contract.title',
            'description' => 'modules.project_contract.description',
        ],
        self::PROJECT_ORGANIZATION => [
            'title' => 'modules.project_organization.title',
            'description' => 'message.project_organization.description',
        ],
        self::PLAN => [
            'title' => 'modules.plan.title',
            'description' => 'modules.plan.description',
        ],
        self::TASK_MANAGEMENT => [
            'title' => 'modules.task_management.title',
            'description' => 'modules.task_management.description',
        ],
        self::PHASES_MILESTONES => [
            'title' => 'modules.phases_milestones.title',
            'description' => 'modules.phases_milestones.description',
        ],
        self::COSTS => [
            'title' => 'modules.costs.title',
            'description' => 'modules.costs.description',
        ],
        self::RESOURCES => [
            'title' => 'modules.resources.title',
            'description' => 'modules.resources.description',
        ],
        self::RISKS_OPPORTUNITIES => [
            'title' => 'modules.risks_opportunities.title',
            'description' => 'modules.risks_opportunities.description',
        ],
        self::COMMUNICATION => [
            'title' => 'modules.communication.title',
            'description' => 'modules.communication.description',
        ],
        self::CONTROL_MEASURES => [
            'title' => 'modules.control_measures.title',
            'description' => 'modules.control_measures.description',
        ],
        self::STATUS_REPORT => [
            'title' => 'modules.status_report.title',
            'description' => 'modules.status_report.description',
        ],
        self::MEETINGS => [
            'title' => 'modules.meetings.title',
            'description' => 'modules.meetings.description',
        ],
        self::TODOS => [
            'title' => 'modules.todos.title',
            'description' => 'modules.todos.description',
        ],
        self::NOTES => [
            'title' => 'modules.notes.title',
            'description' => 'modules.notes.description',
        ],
        self::CLOSE_DOWN_PROJECT => [
           'title' => 'modules.close_down_project.title',
            'description' => 'modules.close_down_project.description',
        ],
        self::RASCI_MATRIX => [
            'title' => 'modules.rasci_matrix.title',
            'description' => 'modules.rasci_matrix.description',
        ],
        self::TASK_CHART => [
            'title' => 'modules.task_chart.title',
            'description' => 'modules.task_chart.description',
        ],
        self::GANTT_CHART => [
            'title' => 'modules.gantt_chart.title',
            'description' => 'modules.gantt_chart.description',
        ],
        self::DECISIONS => [
            'title' => 'modules.decisions.title',
            'description' => 'modules.decisions.description',
        ],
    ];
}
