<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class ProjectUserRepository extends BaseRepository
{
    use ProjectSortingTrait;

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

        $this->setOrder($orderBy, $qb);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        if ($offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    public function getQueryByUserFullName(Project $project, $filters = [], $select = null)
    {
        $qb = $this
            ->createQueryBuilder('q')
            ->where('q.project = :project')
            ->leftJoin('q.user', 'u')
            ->setParameter('project', $project)
        ;

        if ($select) {
            $qb->select($select);
        }

        if (isset($filters['search'])) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('u.firstName', ':searchString'),
                        $qb->expr()->like('u.lastName', ':searchString')
                    )
                )
                ->setParameter('searchString', '%'.$filters['search'].'%')
            ;
        }

        if (isset($filters['users']) && !empty($filters['users'])) {
            $qb->andWhere($qb->expr()->in('u.id', $filters['users']));
        }

        if (isset($filters['pageSize']) && isset($filters['page'])) {
            $qb
                ->setFirstResult($filters['pageSize'] * ($filters['page'] - 1))
                ->setMaxResults($filters['pageSize'])
            ;
        }

        return $qb;
    }

    /**
     * Counts the filtered decisions.
     *
     * @param Project $project
     * @param array   $filters
     *
     * @return int
     */
    public function countTotalByProjectAndFilters(Project $project, $filters = [])
    {
        return (int) $this
            ->getQueryByUserFullName($project, $filters, 'COUNT(DISTINCT q.id)')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
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
