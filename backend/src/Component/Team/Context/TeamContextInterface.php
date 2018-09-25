<?php

namespace Component\Team\Context;

use AppBundle\Entity\Team;

interface TeamContextInterface
{
    /**
     * @return Team|null
     */
    public function getCurrent();

    /**
     * @return string
     */
    public function getCurrentSlug(): string;
}

