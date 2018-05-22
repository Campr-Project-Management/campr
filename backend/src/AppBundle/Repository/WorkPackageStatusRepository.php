<?php

namespace AppBundle\Repository;

use AppBundle\Entity\WorkPackageStatus;
use AppBundle\Repository\Traits\ProjectSortingTrait;

class WorkPackageStatusRepository extends BaseRepository
{
    use ProjectSortingTrait;

    /**
     * @return array
     */
    public function findAllVisible()
    {
        return $this->findBy(['visible' => true]);
    }

    /**
     * @return WorkPackageStatus|null
     */
    public function getDefault()
    {
        /** @var WorkPackageStatus $status */
        $status = $this->findOneBy(['default' => true]);

        return $status;
    }
}
