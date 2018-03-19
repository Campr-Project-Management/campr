<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * TeamInvite.
 *
 * @ORM\Table(name="team_invite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamInviteRepository")
 */
class TeamInvite
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
     * @var Team
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Team", inversedBy="teamInvites")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $team;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="teamInvites")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=32, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var Project|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id")
     * })
     */
    private $project;

    /**
     * @var \DateTime
     * @ORM\Column(name="accepted_at", type="datetime", nullable=true)
     */
    private $acceptedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->token = uniqid(rand(1000, 9999), true);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return TeamInvite
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return TeamInvite
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set team.
     *
     * @param Team $team
     *
     * @return TeamInvite
     */
    public function setTeam(Team $team)
    {
        $this->team = $team;

        return $this;
    }

    /**
     * Get team.
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return TeamInvite
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set token.
     *
     * @param string $token
     *
     * @return TeamInvite
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return \DateTime|null
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }

    /**
     * @param \DateTime $acceptedAt
     *
     * @return TeamInvite
     */
    public function setAcceptedAt(\DateTime $acceptedAt = null)
    {
        $this->acceptedAt = $acceptedAt;

        return $this;
    }

    /**
     * @return Project|null
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     *
     * @return self
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;

        return $this;
    }
}
