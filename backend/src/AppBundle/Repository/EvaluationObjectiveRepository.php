<?php

namespace AppBundle\Repository;

class EvaluationObjectiveRepository extends BaseRepository
{
    /**
     * @param array $objectives
     */
    public function updateSequences(array $objectives)
    {
        $qb = $this->createQueryBuilder('eo');
        foreach ($objectives as $objective) {
            if (isset($objective['id']) && isset($objective['sequence'])) {
                $qb->update()
                    ->set('eo.sequence', ':sequence')
                    ->where('eo.id = :id')
                    ->setParameters(['sequence' => $objective['sequence'], 'id' => $objective['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }
}
