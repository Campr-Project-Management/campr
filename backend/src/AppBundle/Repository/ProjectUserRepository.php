<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;

class ProjectUserRepository extends BaseRepository
{
    public function findByWithLike(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->leftJoin('q.user', 'u');

        foreach ($criteria as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $qb->andWhere(
                $qb->expr()->like(
                    'u.'.$key,
                    $qb->expr()->literal('%'.$value.'%')
                )
            );
        }

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('u.'.$key, $value);
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

    public function getQueryByUserFullName($filters)
    {
        $qb = $this->createQueryBuilder('q')->leftJoin('q.user', 'u');

        if (isset($filters['search'])) {
            $qb
                ->where(
                    $qb->expr()->orX(
                        $qb->expr()->like('u.firstName', ':searchString'),
                        $qb->expr()->like('u.lastName', ':searchString')
                    )
                )
                ->setParameter('searchString', '%'.$filters['search'].'%')
            ;
        }

        if (isset($filters['users']) && !empty($filters['users'])) {
            $qb->andWhere($qb->expr()->in('q.id', $filters['users']));
        }

        if (isset($filters['project'])) {
            $qb
                ->andWhere($qb->expr()->eq('q.project', ':project'))
                ->setParameter('project', $filters['project'])
            ;
        }

        return $qb->getQuery();
    }

    public function getUserAndDepartment(Project $project)
    {
        $qb = $this
            ->createQueryBuilder('pu')
            ->select('u.id as uid, pd.name as department')
            ->innerJoin('pu.user', 'u')
            ->innerJoin('pu.projectDepartments', 'pd')
            ->where('pu.project = :project')
            ->setParameter('project', $project)
        ;

        return $qb->getQuery()->getArrayResult();
    }
}
