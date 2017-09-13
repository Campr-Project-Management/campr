<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Entity\Rasci;
use AppBundle\Entity\User;
use AppBundle\Entity\WorkPackage;

class RasciRepository extends BaseRepository
{
    /**
     * @param Project $project
     *
     * @return Rasci[]
     */
    public function findByProject(Project $project)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->join('r.workPackage', 'wp');
        $qb->where(
            $qb
                ->expr()
                ->eq('wp.project', $project->getId())
        );

        return $qb->getQuery()->getResult();
    }

    /**
     * @param WorkPackage $workPackage
     * @param User        $user
     *
     * @return Rasci|null|object
     */
    public function findOrCreateOneByWorkPackageAndUser(WorkPackage $workPackage, User $user)
    {
        $rasci = $this->findOneBy(['workPackage' => $workPackage, 'user' => $user]);

        if (!$rasci) {
            $rasci = new Rasci();
            $rasci->setWorkPackage($workPackage);
            $rasci->setUser($user);
        }

        return $rasci;
    }

    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->leftJoin('q.workPackage', 'wp')
        ;

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $qb->andWhere(
                $qb->expr()->like(
                    'wp.'.$key,
                    $qb->expr()->literal('%'.$value.'%')
                )
            );
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('wp.'.$key, $value);
            }
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }
}
