<?php

namespace Component\TeamInvite;

use AppBundle\Entity\Project;
use AppBundle\Entity\TeamInvite;

interface TeamInviterInterface
{
    /**
     * @param string  $email
     * @param string  $teamSlug
     * @param Project $project
     *
     * @return TeamInvite
     */
    public function invite(string $email, string $teamSlug, Project $project): TeamInvite;
}
