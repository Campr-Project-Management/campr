<?php

namespace Component\TeamInvite;

use AppBundle\Entity\TeamInvite;

class TeamInviteSender implements TeamInviteSenderInterface
{
    /**
     * @param TeamInvite $invite
     * @param string     $slug
     */
    public function send(TeamInvite $invite, string $slug)
    {
        throw new \InvalidArgumentException('Method not implemented');
    }
}
