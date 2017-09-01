<?php

namespace AppBundle\Repository;

use AppBundle\Entity\ColorStatus;
use AppBundle\Entity\Project;

class ColorStatusRepository extends BaseRepository
{
    /**
     * @TODO: Ensure that this returns the actual status of the project
     *
     * @param Project $project
     *
     * @return ColorStatus|null
     */
    public function findOneByProject(Project $project)
    {
        $qb = $this->createQueryBuilder('cs');
        $qb->innerJoin('cs.workPackages', 'wp');
        $qb->where(
            $qb->expr()->eq(
                'wp.project',
                $project->getId()
            )
        );
        $qb->select('cs, COUNT(DISTINCT wp.id) as HIDDEN c');
        $qb->orderBy('c', 'desc');
        $qb->groupBy('cs.id');
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
