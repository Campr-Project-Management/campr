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
    const RACI_MATRIX = 'raci_matrix';
    const TASK_CHART = 'task_chart';
    const GANTT_CHART = 'gantt_chart';
    const CONTEXT = 'context';
    const DECISIONS = 'decisions';

    const ELEMENTS = [
        self::PROJECT_CONTRACT => 'message.project_contract',
        self::PROJECT_ORGANIZATION => 'message.project_organization',
        self::PLAN => 'message.plan',
        self::TASK_MANAGEMENT => 'message.task_management',
        self::PHASES_MILESTONES => 'message.phases_milestones',
        self::COSTS => 'message.costs',
        self::RESOURCES => 'message.resources',
        self::RISKS_OPPORTUNITIES => 'message.risks_opportunities',
        self::COMMUNICATION => 'message.communication',
        self::CONTROL_MEASURES => 'message.control_measures',
        self::STATUS_REPORT => 'message.status_report',
        self::MEETINGS => 'message.meetings',
        self::TODOS => 'message.todos',
        self::NOTES => 'message.notes',
        self::CLOSE_DOWN_PROJECT => 'message.close_down_project',
        self::RACI_MATRIX => 'message.raci_matrix',
        self::TASK_CHART => 'message.task_chart',
        self::GANTT_CHART => 'message.gantt_chart',
        self::CONTEXT => 'message.context',
        self::DECISIONS => 'message.decisions',
    ];
}
