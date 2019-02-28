<?php

namespace Component\Project;

use AppBundle\Entity\WorkPackage;
use Component\Currency\Model\CurrencyAwareInterface;
use Component\Resource\Model\FileSystemAwareInterface;
use Component\Resource\Model\ResourceInterface;

interface ProjectInterface extends CurrencyAwareInterface, FileSystemAwareInterface, ResourceInterface
{
    /**
     * @param WorkPackage $workPackage
     */
    public function addWorkPackage(WorkPackage $workPackage);

    /**
     * @return WorkPackage[]
     */
    public function getWorkPackages();
}
