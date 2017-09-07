<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\ContractSortingTrait;

class ProjectObjectiveRepository extends BaseRepository
{
    use ProjectSortingTrait, ContractSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        ContractSortingTrait::setOrder as setContractOrder;
    }

    /**
     * @param array $objectives
     */
    public function updateSequences(array $objectives)
    {
        $qb = $this->createQueryBuilder('po');
        foreach ($objectives as $objective) {
            if (isset($objective['id']) && isset($objective['sequence'])) {
                $qb->update()
                    ->set('po.sequence', ':sequence')
                    ->where('po.id = :id')
                    ->setParameters(['sequence' => $objective['sequence'], 'id' => $objective['id']])
                    ->getQuery()
                    ->execute()
                ;
            }
        }
    }

    /**
     * @param array        $orderBy
     * @param QueryBuilder $qb
     */
    public function setOrder(array &$orderBy, QueryBuilder $qb)
    {
        $this->setProjectOrder($orderBy, $qb);
        $this->setContractOrder($orderBy, $qb);
    }
}
