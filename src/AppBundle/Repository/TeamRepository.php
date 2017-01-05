<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Team;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    /**
     * @param $id
     *
     * @return null|Team
     */
    public function findOneByIDOrSlug($id): Team
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->eq('t.id', $qb->expr()->literal($id)));
        $qb->orWhere($qb->expr()->eq('t.slug', $qb->expr()->literal($id)));
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findByTeamMember(User $user)
    {
        $qb = $this->createQueryBuilder('t');

        return $qb
            ->join('t.teamMembers', 'tm')
            ->where('tm.user = :user')
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult()
        ;
    }
}
