<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectInnovationDegreeModuleAnalyser extends AbstractProjectModuleAnalyser
{
    const TYPE = 'project_innovation_degree';

    const VALUE_ENHANCEMENTS = 0;

    const VALUE_INCREMENTAL = 1;

    const VALUE_STRATEGIC_INNOVATION = 2;

    const VALUE_RADICAL_INNOVATION = 3;

    private static $criterias = [
        ProjectModules::MODULE_CONTRACT => [
            'value >= enhancements' => true,
        ],
        ProjectModules::MODULE_ORGANIZATION => [
            'value >= enhancements' => true,
        ],
        ProjectModules::MODULE_PHASES_AND_MILESTONES => [
            'value >= incremental' => true,
        ],
        ProjectModules::MODULE_TASK_MANAGEMENT => [
            'value >= enhancements' => true,
        ],
        ProjectModules::MODULE_RASCI_MATRIX => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_WBS => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_RISKS_AND_OPPORTUNITIES => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_MEETINGS => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_TODOS => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_INFOS => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_DECISIONS => [
            'value >= strategic_innovation' => true,
        ],
        ProjectModules::MODULE_STATUS_REPORT => [
            'value >= enhancements' => true,
        ],
        ProjectModules::MODULE_CLOSE_DOWN_PROJECT => [
            'value >= enhancements' => true,
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
     * @param string $expr
     * @param array  $values
     *
     * @return bool
     */
    protected function evaluate(string $expr, array $values): bool
    {
        $values['enhancements'] = self::VALUE_ENHANCEMENTS;
        $values['incremental'] = self::VALUE_INCREMENTAL;
        $values['strategic_innovation'] = self::VALUE_STRATEGIC_INNOVATION;
        $values['radical_innovation'] = self::VALUE_RADICAL_INNOVATION;

        return parent::evaluate($expr, $values);
    }

    /**
     * @param int $value
     *
     * @return bool
     */
    protected function supportsValue(int $value): bool
    {
        return in_array($value,
            [
                self::VALUE_ENHANCEMENTS,
                self::VALUE_INCREMENTAL,
                self::VALUE_STRATEGIC_INNOVATION,
                self::VALUE_RADICAL_INNOVATION,
            ]
        );
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
