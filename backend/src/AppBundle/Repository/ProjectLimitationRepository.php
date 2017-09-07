<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\Traits\ProjectSortingTrait;
use AppBundle\Repository\Traits\ContractSortingTrait;

class ProjectLimitationRepository extends BaseRepository
{
    use ProjectSortingTrait, ContractSortingTrait {
        ProjectSortingTrait::setOrder as setProjectOrder;
        ContractSortingTrait::setOrder as setContractOrder;
    }

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
