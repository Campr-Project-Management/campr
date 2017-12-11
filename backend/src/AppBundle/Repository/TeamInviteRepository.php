<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Team;
use Doctrine\ORM\EntityRepository;

class TeamInviteRepository extends EntityRepository
{
    public function findOneByEmailAndTeam(string $email, Team $team)
    {
        $qb = $this->createQueryBuilder('ti');
        $qb->leftJoin('ti.user', 'u');

        $qb->where(
            $qb
                ->expr()
                ->andX(
                    $qb
                        ->expr()
                        ->eq(
                            'ti.team',
                            $team->getId()
                        ),
                    $qb
                        ->expr()
                        ->isNull('ti.acceptedAt')
                )
        );
        $qb->andWhere(
            $qb
                ->expr()
                ->orX(
                    $qb
                        ->expr()
                        ->eq(
                            'ti.email',
                            $qb->expr()->literal($email)
                        ),
                    $qb
                        ->expr()
                        ->eq(
                            'u.email',
                            $qb->expr()->literal($email)
                        )
                )
        );
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
