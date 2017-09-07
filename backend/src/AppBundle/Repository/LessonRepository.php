<?php

namespace AppBundle\Repository;

class LessonRepository extends BaseRepository
{
    /**
     * @param array $objectives
     */
    public function updateSequences(array $objectives)
    {
        $qb = $this->createQueryBuilder('l');
        foreach ($objectives as $objective) {
            if (isset($objective['id']) && isset($objective['sequence'])) {
                $qb->update()
                    ->set('l.sequence', ':sequence')
                    ->where('l.id = :id')
                    ->setParameters(['sequence' => $objective['sequence'], 'id' => $objective['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }
}
