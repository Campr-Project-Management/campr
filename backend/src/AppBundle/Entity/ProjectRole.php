<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProjectRole.
 *
 * @ORM\Table(name="project_role")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRoleRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 */
class ProjectRole
{
    const ROLE_SPONSOR = 'ROLE_SPONSOR';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_TEAM_MEMBER = 'ROLE_TEAM_MEMBER';
    const ROLE_TEAM_LEADER = 'ROLE_TEAM_LEADER';

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="integer", nullable=false, options={"default"=0})
     */
    private $sequence = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_lead", type="boolean")
     */
    private $isLead;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var ProjectUser[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ProjectUser", mappedBy="projectRoles")
     * @Serializer\Exclude()
     */
    private $projectUsers;

    /**
     * ProjectRole constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isLead = false;
        $this->projectUsers = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return ProjectRole
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return ProjectRole
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence.
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * Set isLead.
     *
     * @param bool $isLead
     *
     * @return ProjectRole
     */
    public function setIsLead($isLead)
    {
        $this->isLead = $isLead;

        return $this;
    }

    /**
     * Get isLead.
     *
     * @return bool
     */
    public function getIsLead()
    {
        return $this->isLead;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ProjectRole
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
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return ProjectRole
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add projectUser.
     *
     * @param ProjectUser $projectUser
     *
     * @return ProjectRole
     */
    public function addProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;

        return $this;
    }

    /**
     * Remove projectUser.
     *
     * @param ProjectUser $projectUser
     */
    public function removeProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers->removeElement($projectUser);
    }

    /**
     * Get projectUsers.
     *
     * @return ArrayCollection|ProjectUser[]
     */
    public function getProjectUsers()
    {
        return $this->projectUsers;
    }
}
