<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubteamRepository")
 * @ORM\Table(name="subteam")
 */
class Subteam
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     * @Assert\NotBlank(message="not_blank.subteam.name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Project
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="subteams")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $project;

    /**
     * @var SubteamMember[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SubteamMember", mappedBy="subteam", orphanRemoval=true, cascade={"all"})
     */
    private $subteamMembers;

    /**
     * @var Subteam
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subteam", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var ArrayCollection|Subteam[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Subteam", mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;

    /**
     * @var ProjectDepartment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectDepartment", inversedBy="subteams")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $department;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->name;
    }
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->subteamMembers = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * Set parent.
     *
     * @param Subteam $parent
     *
     * @return Subteam
     */
    public function setParent(Subteam $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Subteam
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param Subteam $child
     *
     * @return Subteam
     */
    public function addChild(Subteam $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param Subteam $child
     */
    public function removeChild(Subteam $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return ArrayCollection|Subteam[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Subteam
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
     * Set description.
     *
     * @param string $description
     *
     * @return Subteam
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set project.
     *
     * @param \AppBundle\Entity\Project $project
     *
     * @return Subteam
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add subteamMember.
     *
     * @param SubteamMember $subteamMember
     *
     * @return Subteam
     */
    public function addSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers[] = $subteamMember;
        $subteamMember->setSubteam($this);

        return $this;
    }

    /**
     * Remove subteamMember.
     *
     * @param SubteamMember $subteamMember
     *
     * @return Subteam
     */
    public function removeSubteamMember(SubteamMember $subteamMember)
    {
        $this->subteamMembers->removeElement($subteamMember);
        $subteamMember->setSubteam(null);

        return $this;
    }

    /**
     * Get subteamMembers.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubteamMembers()
    {
        return $this->subteamMembers;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return int|null
     */
    public function getProjectId()
    {
        return $this->project ? $this->project->getId() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectName")
     *
     * @return string|null
     */
    public function getProjectName()
    {
        return $this->project ? (string) $this->project : null;
    }

    /**
     * @return ProjectDepartment|null
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * @param ProjectDepartment $department
     */
    public function setDepartment(ProjectDepartment $department = null)
    {
        $this->department = $department;
    }
}
