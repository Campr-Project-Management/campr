<?php

namespace AppBundle\Entity;

use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProjectRole.
 *
 * @ORM\Table(name="project_role",  uniqueConstraints={
 *     @ORM\UniqueConstraint(name="name_project_unique", columns={"name", "project_id"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRoleRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 */
class ProjectRole implements ProjectAwareInterface, ResourceInterface, CloneableInterface, TimestampableInterface
{
    use TimestampableTrait;

    const ROLE_SPONSOR = 'roles.project_sponsor';
    const ROLE_MANAGER = 'roles.project_manager';
    const ROLE_TEAM_MEMBER = 'roles.team_member';
    const ROLE_TEAM_LEADER = 'roles.team_leader';

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
     * @ORM\Column(name="name", type="string", length=255)
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
     * @var ProjectRole
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectRole", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var ArrayCollection|ProjectRole[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectRole", mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;

    /**
     * @var Project|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="projectRoles")
     * @ORM\JoinColumn(name="project_id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

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
        $this->children = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
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
     * Set parent.
     *
     * @param ProjectRole $parent
     *
     * @return ProjectRole
     */
    public function setParent(ProjectRole $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return ProjectRole
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param ProjectRole $child
     *
     * @return ProjectRole
     */
    public function addChild(ProjectRole $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param ProjectRole $child
     */
    public function removeChild(ProjectRole $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return ArrayCollection|ProjectRole[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return ProjectRole
     */
    public function setProject(ProjectInterface $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
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
