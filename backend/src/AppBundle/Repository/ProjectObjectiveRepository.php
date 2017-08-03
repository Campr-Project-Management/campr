<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectObjectiveRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * @param array $objectives
     */
    public function updateSequences(array $objectives)
    {
        $qb = $this->createQueryBuilder('po');
        foreach ($objectives as $objective) {
            if (isset($objective['id']) && isset($objective['sequence'])) {
                $qb->update()
                    ->set('po.sequence', ':sequence')
                    ->where('po.id = :id')
                    ->setParameters(['sequence' => $objective['sequence'], 'id' => $objective['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }
}
