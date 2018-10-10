<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectBudgetModuleAnalyser extends AbstractProjectModuleAnalyser
{
    const TYPE = 'project_budget';

    const MIN_VALUE = 10000;

    const MAX_VALUE = 500000 * 1000;

    private static $criterias = [
        ProjectModules::MODULE_CONTRACT => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_ORGANIZATION => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_TASK_MANAGEMENT => [
            'value >= 0' => true,
        ],
        ProjectModules::MODULE_RASCI_MATRIX => [
            'value >= 1000000' => true,
        ],
        ProjectModules::MODULE_INTERNAL_COSTS => [
            'value >= 150000' => true,
        ],
        ProjectModules::MODULE_EXTERNAL_COSTS => [
            'value >= 150000' => true,
        ],
        ProjectModules::MODULE_MEETINGS => [
            'value >= 5000000' => true,
        ],
        ProjectModules::MODULE_TODOS => [
            'value >= 5000000' => true,
        ],
        ProjectModules::MODULE_INFOS => [
            'value >= 5000000' => true,
        ],
        ProjectModules::MODULE_DECISIONS => [
            'value >= 5000000' => true,
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
