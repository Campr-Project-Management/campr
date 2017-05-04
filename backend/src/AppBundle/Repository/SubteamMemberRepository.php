<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\User;

class SubteamMemberRepository extends BaseRepository
{
    public function findByUserAndProject(User $user, Project $project)
    {
        return $this
            ->createQueryBuilder('sm')
            ->innerJoin('sm.subteam', 's')
            ->where('sm.user = :user')
            ->andWhere('s.project = :project')
            ->setParameters(['user' => $user, 'project' => $project])
            ->getQuery()
            ->getResult()
        ;
    }
}
