<?php

namespace AppBundle\Entity;

use Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * TeamSlug.
 *
 * @ORM\Table(name="team_slug")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamSlugRepository")
 * @Cloner\Exclude()
 */
class TeamSlug implements ResourceInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var Team
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="teamSlugs")
     * @ORM\JoinColumn(name="team_id", onDelete="CASCADE")
     */
    private $team;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;
    }
}
