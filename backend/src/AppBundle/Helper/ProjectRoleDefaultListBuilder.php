<?php

namespace AppBundle\Helper;

use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\Project;
use Doctrine\Common\Collections\ArrayCollection;

class ProjectRoleDefaultListBuilder
{
    public static function buildDefaultListForProject(Project $project)
    {
        $defaultList = [];
        $defaultList[] = (new ProjectRole())
             ->setName(ProjectRole::ROLE_MANAGER)
             ->setProject($project)
        ;

        $defaultList[] = (new ProjectRole())
            ->setName(ProjectRole::ROLE_SPONSOR)
            ->setProject($project)
        ;

        $defaultList[] = (new ProjectRole())
             ->setName(ProjectRole::ROLE_TEAM_LEADER)
             ->setProject($project)
        ;

        $defaultList[] = (new ProjectRole())
            ->setName(ProjectRole::ROLE_TEAM_MEMBER)
            ->setProject($project)
        ;

        return $defaultList;
    }
}
