<?php

namespace AppBundle\Repository;

use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectDeliverableRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * @param array $deliverables
     */
    public function updateSequences(array $deliverables)
    {
        $qb = $this->createQueryBuilder('d');
        foreach ($deliverables as $deliverable) {
            if (isset($deliverable['id']) && isset($deliverable['sequence'])) {
                $qb->update()
                    ->set('d.sequence', ':sequence')
                    ->where('d.id = :id')
                    ->setParameters(['sequence' => $deliverable['sequence'], 'id' => $deliverable['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }
}
