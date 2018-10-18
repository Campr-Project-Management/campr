<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectTechnologyComplexityModuleAnalyser extends AbstractProjectModuleAnalyser
{
    const TYPE = 'project_technology_complexity';

    const VALUE_LOW = 0;

    const VALUE_MEDIUM = 1;

    const VALUE_HIGH = 2;

    private static $criterias = [
        ProjectModules::MODULE_CONTRACT => [
            'value >= low' => true,
        ],
        ProjectModules::MODULE_ORGANIZATION => [
            'value >= low' => true,
        ],
        ProjectModules::MODULE_PHASES_AND_MILESTONES => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_TASK_MANAGEMENT => [
            'value >= low' => true,
        ],
        ProjectModules::MODULE_RASCI_MATRIX => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_WBS => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_RISKS_AND_OPPORTUNITIES => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_MEETINGS => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_TODOS => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_INFOS => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_DECISIONS => [
            'value >= medium' => true,
        ],
        ProjectModules::MODULE_STATUS_REPORT => [
            'value >= low' => true,
        ],
        ProjectModules::MODULE_CLOSE_DOWN_PROJECT => [
            'value >= low' => true,
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
        return in_array($value, [self::VALUE_LOW, self::VALUE_MEDIUM, self::VALUE_HIGH]);
    }

    /**
     * @param string $expr
     * @param array  $values
     *
     * @return bool
     */
    protected function evaluate(string $expr, array $values): bool
    {
        $values['low'] = self::VALUE_LOW;
        $values['medium'] = self::VALUE_MEDIUM;
        $values['high'] = self::VALUE_HIGH;

        return parent::evaluate($expr, $values);
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
