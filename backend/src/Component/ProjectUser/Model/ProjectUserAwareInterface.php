<?php

namespace Component\ProjectUser\Model;

use AppBundle\Entity\ProjectUser;

interface ProjectUserAwareInterface
{
    /**
     * @return ProjectUser
     */
    public function getProjectUser();
}
