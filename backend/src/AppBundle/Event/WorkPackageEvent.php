<?php

namespace AppBundle\Event;

use AppBundle\Entity\WorkPackage;
use Symfony\Component\EventDispatcher\Event;

class WorkPackageEvent extends Event
{
    /**
     * @var WorkPackage
     */
    private $workPackage;

    /**
     * WorkPackageEvent constructor.
     *
     * @param WorkPackage $workPackage
     */
    public function __construct(WorkPackage $workPackage)
    {
        $this->workPackage = $workPackage;
    }

    /**
     * @return WorkPackage
     */
    public function getWorkPackage(): WorkPackage
    {
        return $this->workPackage;
    }
}
