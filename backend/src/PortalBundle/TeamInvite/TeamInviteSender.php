<?php

namespace PortalBundle\TeamInvite;

use AppBundle\Entity\TeamInvite;
use Component\TeamInvite\TeamInviteSenderInterface;
use PortalBundle\Client\Http\WorkspaceInviteClient;
use Psr\Log\LoggerInterface;

class TeamInviteSender implements TeamInviteSenderInterface
{
    /**
     * @var WorkspaceInviteClient
     */
    private $workspaceInviteClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * TeamInviteSender constructor.
     *
     * @param WorkspaceInviteClient $workspaceInviteClient
     * @param LoggerInterface       $logger
     */
    public function __construct(WorkspaceInviteClient $workspaceInviteClient, LoggerInterface $logger)
    {
        $this->workspaceInviteClient = $workspaceInviteClient;
        $this->logger = $logger;
    }

    /**
     * @param TeamInvite $invite
     * @param string     $slug
     *
     * @throws \Throwable
     */
    public function send(TeamInvite $invite, string $slug)
    {
        $logContext = [
            'email' => $invite->getEmail(),
        ];

        try {
            $this->logger->info('Sending team invite', $logContext);

            $this->workspaceInviteClient->create($slug, $invite->getEmail(), $invite->getInviter());

            $this->logger->info('Team invite successfully sent', $logContext);
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), $logContext);

            throw $e;
        }
    }
}
