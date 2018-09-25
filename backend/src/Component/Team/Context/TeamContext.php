<?php

namespace Component\Team\Context;

use AppBundle\Entity\Team;
use AppBundle\Repository\TeamRepository;

class TeamContext implements TeamContextInterface
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * TeamContext constructor.
     *
     * @param string         $slug
     * @param TeamRepository $teamRepository
     */
    public function __construct(string $slug, TeamRepository $teamRepository)
    {
        $this->slug = $slug;
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return Team|null
     */
    public function getCurrent()
    {
        $slug = $this->getCurrentSlug();
        if (empty($slug)) {
            return null;
        }

        /** @var Team $team */
        $team = $this->teamRepository->findOneBy(['slug' => $slug]);

        return $team;
    }

    /**
     * @return string
     */
    public function getCurrentSlug(): string
    {
        return (string) $this->slug;
    }
}
