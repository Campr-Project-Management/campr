<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;

class ProjectModulesResolver
{
    /**
     * @var ProjectModuleAnalyserInterface
     */
    private $projectModuleAnalyser;

    /**
     * ProjectModulesResolver constructor.
     *
     * @param ProjectModuleAnalyserInterface $projectModuleAnalyser
     */
    public function __construct(ProjectModuleAnalyserInterface $projectModuleAnalyser)
    {
        $this->projectModuleAnalyser = $projectModuleAnalyser;
    }

    /**
     * @param string[] $data
     *
     * @return array
     */
    public function resolve(array $data): array
    {
        $modules = [];
        if (empty($data)) {
            return $modules;
        }

        foreach (ProjectModules::MODULES as $module) {
            foreach ($data as $analyserType => $value) {
                if ($this->projectModuleAnalyser->analyse($module, $value, $analyserType)) {
                    $modules[] = $module;
                    break;
                }
            }
        }

        return $modules;
    }
}
