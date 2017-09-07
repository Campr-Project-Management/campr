<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\ContractSortingTrait;

class ProjectDeliverableRepository extends BaseRepository
{
    use ProjectSortingTrait, ContractSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        ContractSortingTrait::setOrder as setContractOrder;
    }

    /**
     * @param array $deliverables
     */
    public function updateSequences(array $deliverables)
    {
        $qb = $this->createQueryBuilder('d');
        foreach ($deliverables as $deliverable) {
            if (isset($deliverable['id']) && isset($deliverable['sequence'])) {
                $qb->update()
                    ->set('d.sequence', ':sequence')
                    ->where('d.id = :id')
                    ->setParameters(['sequence' => $deliverable['sequence'], 'id' => $deliverable['id']])
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
