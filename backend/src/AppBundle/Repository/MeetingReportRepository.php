<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Meeting;

class MeetingReportRepository extends BaseRepository
{
    public function findLastByMeeting(Meeting $meeting)
    {
        $qb = $this->createQueryBuilder('r');

        return $qb
            ->where('r.meeting = :meeting')
            ->setParameter('meeting', $meeting)
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
