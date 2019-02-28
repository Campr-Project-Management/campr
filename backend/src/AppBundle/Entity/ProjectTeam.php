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
use Component\Resource\Cloner\Annotation as Cloner;

/**
 * ProjectTeam.
 *
 * @ORM\Table(name="project_team")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectTeamRepository")
 * @UniqueEntity(fields="name", message="unique.name")
 * @Cloner\Exclude()
 */
class ProjectTeam implements ProjectAwareInterface, ResourceInterface, CloneableInterface, TimestampableInterface
{
    use TimestampableTrait;

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
     * @var Project|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="projectTeams")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", nullable=false, onDelete="CASCADE")
     * })
     */
    private $project;

    /**
     * @var ProjectTeam
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectTeam", inversedBy="children")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="parent_id", onDelete="CASCADE")
     * })
     */
    private $parent;

    /**
     * @var ArrayCollection|ProjectTeam[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectTeam", mappedBy="parent")
     */
    private $children;

    /**
     * ProjectTeam constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * @return ProjectTeam
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
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return ProjectTeam
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
     * Set parent.
     *
     * @param ProjectTeam $parent
     *
     * @return ProjectTeam
     */
    public function setParent(ProjectTeam $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return ProjectTeam
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param ProjectTeam $child
     *
     * @return ProjectTeam
     */
    public function addChild(ProjectTeam $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param ProjectTeam $child
     */
    public function removeChild(ProjectTeam $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return ArrayCollection|ProjectTeam[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Returns project id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->project ? $this->project->getId() : null;
    }

    /**
     * Returns project name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project ? $this->project->getName() : null;
    }

    /**
     * Returns parent id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("parent")
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->parent ? $this->parent->getId() : null;
    }

    /**
     * Returns parent name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("parentName")
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parent ? $this->parent->getName() : null;
    }
}
