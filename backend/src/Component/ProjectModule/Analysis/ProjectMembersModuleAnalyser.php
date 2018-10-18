<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectMembersModuleAnalyser extends AbstractProjectModuleAnalyser
{
    const TYPE = 'project_members';

    const MIN_VALUE = 3;

    const MAX_VALUE = 121;

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
            'value >= 10' => true,
        ],
        ProjectModules::MODULE_MEETINGS => [
            'value >= 10' => true,
        ],
        ProjectModules::MODULE_TODOS => [
            'value >= 10' => true,
        ],
        ProjectModules::MODULE_INFOS => [
            'value >= 10' => true,
        ],
        ProjectModules::MODULE_DECISIONS => [
            'value >= 10' => true,
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
