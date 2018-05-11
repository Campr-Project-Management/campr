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
    public function findOneByIDOrSlug($id)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->eq('t.id', $qb->expr()->literal($id)));
        $qb->orWhere($qb->expr()->eq('t.slug', $qb->expr()->literal($id)));
        $qb->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $slug
     *
     * @return null|Team
     */
    public function findOneBySlug(string $slug)
    {
        return $this->findOneBy(['slug' => $slug]);
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

    public function findDeletedTeams(\DateInterval $deletedBefore = null)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $qb = $this->createQueryBuilder('t');
        $qb->andWhere(
            $qb
                ->expr()
                ->isNotNull('t.deletedAt')
        );

        if ($deletedBefore) {
            $dt = new \DateTime();
            $dt->sub($deletedBefore);
            $qb
                ->andWhere(
                    $qb
                        ->expr()
                        ->lte(
                            't.deletedAt',
                            $qb
                                ->expr()
                                ->literal(
                                    $dt->format('Y-m-d H:i:s')
                                )
                        )
                )
            ;
        }

        $teams = $qb->getQuery()->getResult();

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $teams;
    }

    public function findDeletedTeam($id)
    {
        $this->getEntityManager()->getFilters()->disable('softdeleteable');

        $team = $this->find($id);

        $this->getEntityManager()->getFilters()->enable('softdeleteable');

        return $team->getDeletedAt() ? $team : null;
    }

    public function permanentlyRemove(Team $team)
    {
        $em = $this->getEntityManager();
        $em->getFilters()->disable('softdeleteable');

        $qb = $em->createQueryBuilder()
            ->delete(Team::class, 't')
            ->where('t.id = :team_id')
            ->setParameter('team_id', $team->getId());

        $qb->getQuery()->execute();

        $em->getFilters()->enable('softdeleteable');
    }
}
