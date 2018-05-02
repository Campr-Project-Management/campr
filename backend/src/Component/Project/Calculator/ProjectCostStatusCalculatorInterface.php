<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;

interface ProjectCostStatusCalculatorInterface
{
    /**
     * @param Project $project
     *
     * @return float
     */
    public function calculate(Project $project): float;
}
