<?php

namespace Component\ProjectModule;

final class ProjectModules
{
    const MODULE_CONTRACT = 'contract';
    const MODULE_ORGANIZATION = 'organization';
    const MODULE_PHASES_AND_MILESTONES = 'phases_and_milestones';
    const MODULE_TASK_MANAGEMENT = 'task_management';
    const MODULE_INTERNAL_COSTS = 'internal_costs';
    const MODULE_EXTERNAL_COSTS = 'external_costs';
    const MODULE_RISKS_AND_OPPORTUNITIES = 'risks_and_opportunities';
    const MODULE_GANTT_CHART = 'gantt_chart';
    const MODULE_RASCI_MATRIX = 'rasci_matrix';
    const MODULE_WBS = 'wbs';
    const MODULE_MEETINGS = 'meetings';
    const MODULE_TODOS = 'todos';
    const MODULE_INFOS = 'infos';
    const MODULE_DECISIONS = 'decisions';
    const MODULE_STATUS_REPORT = 'status_report';
    const MODULE_CLOSE_DOWN_PROJECT = 'close_down_project';

    const MODULES = [
       self::MODULE_CONTRACT,
       self::MODULE_ORGANIZATION,
       self::MODULE_PHASES_AND_MILESTONES,
       self::MODULE_TASK_MANAGEMENT,
       self::MODULE_INTERNAL_COSTS,
       self::MODULE_EXTERNAL_COSTS,
       self::MODULE_RISKS_AND_OPPORTUNITIES,
       self::MODULE_GANTT_CHART,
       self::MODULE_RASCI_MATRIX,
       self::MODULE_WBS,
       self::MODULE_MEETINGS,
       self::MODULE_TODOS,
       self::MODULE_INFOS,
       self::MODULE_DECISIONS,
       self::MODULE_STATUS_REPORT,
       self::MODULE_CLOSE_DOWN_PROJECT,
    ];
}
