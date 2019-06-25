<?php

namespace Component\Team\Context;

use AppBundle\Entity\Team;
use Component\Repository\RepositoryInterface;

class TeamContext implements TeamContextInterface
{
    /**
     * @var string
     */
    private $slug;

    /**
     * @var RepositoryInterface
     */
    private $teamRepository;

    /**
     * TeamContext constructor.
     *
     * @param string              $slug
     * @param RepositoryInterface $teamRepository
     */
    public function __construct(string $slug, RepositoryInterface $teamRepository)
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

        return $this->getTeamBySlug($slug);
    }

    /**
     * @return string
     */
    public function getCurrentSlug(): string
    {
        return (string) $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Team|null
     */
    private function getTeamBySlug(string $slug)
    {
        /** @var Team $team */
        $team = $this->teamRepository->findOneBy(['slug' => $slug]);

        return $team;
    }
}
