<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;

class ProjectRoleRepository extends BaseRepository
{
    /**
     * @param Project $project
     *
     * @return ProjectRole
     */
    public function getSponsor(Project $project)
    {
        /** @var ProjectRole $role */
        $role = $this->findOneBy(
            [
                'project' => $project,
                'name' => ProjectRole::ROLE_SPONSOR,
            ]
        );

        return $role;
    }
}
