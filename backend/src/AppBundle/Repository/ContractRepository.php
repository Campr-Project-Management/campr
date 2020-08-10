<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Contract;
use AppBundle\Entity\Project;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class ContractRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * Create contract by project with startDate and endDate by project duration
     *
     * @param Project $project
     * @param int $duration
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createByProject(Project $project, $duration = 0): void
    {
        $contract = new Contract();
        $contract->setName($project->getName() . ' - contract');
        $contract->setCreatedBy($project->getCreatedBy());
        $contract->setProject($project);

        $startDate = new \DateTime();
        $endDate = (new \DateTime())->modify("+{$duration} month");

        $contract->setForecastStartDate($startDate);
        $contract->setForecastEndDate($endDate);

        $contract->setProposedStartDate($startDate);
        $contract->setProposedEndDate($endDate);

        $em = $this->getEntityManager();
        $em->persist($contract);
        $em->flush();
    }
}
