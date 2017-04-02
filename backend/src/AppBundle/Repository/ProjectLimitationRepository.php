<?php

namespace AppBundle\Repository;

class ProjectLimitationRepository extends BaseRepository
{
    /**
     * @param array $limitations
     */
    public function updateSequences(array $limitations)
    {
        $qb = $this->createQueryBuilder('l');
        foreach ($limitations as $limitation) {
            if (isset($limitation['id']) && isset($limitation['sequence'])) {
                $qb->update()
                    ->set('l.sequence', ':sequence')
                    ->where('l.id = :id')
                    ->setParameters(['sequence' => $limitation['sequence'], 'id' => $limitation['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }
}
