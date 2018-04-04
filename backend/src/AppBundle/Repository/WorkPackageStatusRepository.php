<?php

namespace AppBundle\Repository;

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
}
