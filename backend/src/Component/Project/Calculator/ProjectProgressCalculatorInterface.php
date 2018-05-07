<?php

namespace Component\Project\Calculator;

use AppBundle\Entity\Project;

interface ProjectProgressCalculatorInterface
{
    /**
     * @param Project $project
     *
     * @return float
     */
    public function calculate(Project $project): float;
}
