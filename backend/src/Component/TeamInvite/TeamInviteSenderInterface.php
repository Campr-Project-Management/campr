<?php

namespace Component\TeamInvite;

use AppBundle\Entity\TeamInvite;

interface TeamInviteSenderInterface
{
    /**
     * @param TeamInvite $invite
     * @param string     $slug
     */
    public function send(TeamInvite $invite, string $slug);
}
