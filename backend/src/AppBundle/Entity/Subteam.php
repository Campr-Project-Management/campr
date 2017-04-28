<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
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
     * @Assert\NotBlank()
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
     *     @ORM\JoinColumn(name="proect_id", referencedColumnName="id")
     * })
     * @Serializer\Exclude()
     */
    private $project;

    /**
     * @var SubteamRole[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SubteamMember", mappedBy="subteam", orphanRemoval=true, cascade={"all"})
     */
    private $subteamMembers;

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
        $this->subteamMembers = new \Doctrine\Common\Collections\ArrayCollection();
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
}
