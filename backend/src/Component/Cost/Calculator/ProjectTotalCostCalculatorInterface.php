<?php

namespace Component\Cost\Calculator;

use AppBundle\Entity\Project;

interface ProjectTotalCostCalculatorInterface
{
    /**
     * @param Project $project
     *
     * @return ProjectTotalCost
     */
    public function calculate(Project $project): ProjectTotalCost;
}
