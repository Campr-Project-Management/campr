<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectDurationModuleAnalyser extends AbstractProjectModuleAnalyser
{
    const TYPE = 'project_duration';

    const MIN_VALUE = 1;

    const MAX_VALUE = 37;

    private static $criterias = [
        ProjectModules::MODULE_CONTRACT => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_ORGANIZATION => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_PHASES_AND_MILESTONES => [
            'value >= 10' => true,
        ],
        ProjectModules::MODULE_TASK_MANAGEMENT => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_GANTT_CHART => [
            'value >= 7' => true,
        ],
        ProjectModules::MODULE_STATUS_REPORT => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_CLOSE_DOWN_PROJECT => [
            'value >= 0' => true,
        ],
    ];

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    protected function supportsValue(int $value): bool
    {
        return $value >= self::MIN_VALUE && $value <= self::MAX_VALUE;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    protected function getCriterias(string $module): array
    {
        return static::$criterias[$module] ?? [];
    }
}
