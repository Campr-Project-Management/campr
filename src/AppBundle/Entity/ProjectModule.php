<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * ProjectModule.
 *
 * @ORM\Table(name="project_module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectModuleRepository")
 */
class ProjectModule
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
     * @var Project
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;

    /**
     * @var int
     *
     * @ORM\Column(name="module", type="string", length=32)
     */
    private $module;

    /**
     * @var bool
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @var bool
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="is_required", type="boolean", nullable=true)
     */
    private $isRequired;

    /**
     * @var bool
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="is_print", type="boolean", nullable=true)
     */
    private $isPrint;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * ProjectModule constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Set module.
     *
     * @param string $module
     *
     * @return ProjectModule
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module.
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set isEnabled.
     *
     * @param bool $isEnabled
     *
     * @return ProjectModule
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled.
     *
     * @return bool
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set isRequired.
     *
     * @param bool $isRequired
     *
     * @return ProjectModule
     */
    public function setIsRequired($isRequired)
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    /**
     * Get isRequired.
     *
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->isRequired;
    }

    /**
     * Set isPrint.
     *
     * @param bool $isPrint
     *
     * @return ProjectModule
     */
    public function setIsPrint($isPrint)
    {
        $this->isPrint = $isPrint;

        return $this;
    }

    /**
     * Get isPrint.
     *
     * @return bool
     */
    public function getIsPrint()
    {
        return $this->isPrint;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return ProjectModule
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
     * @return ProjectModule
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
     * Set project.
     *
     * @param Project $project
     *
     * @return ProjectModule
     */
    public function setProject(Project $project = null)
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
     * Returns project name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project ? $this->project->getName() : null;
    }

    /**
     * Returns createdAt date formatted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdAt")
     *
     * @return string
     */
    public function getCreatedAtFormatted()
    {
        return $this->createdAt ? $this->createdAt->format('d/m/Y') : null;
    }

    /**
     * Returns updatedAt date formatted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("updatedAt")
     *
     * @return string
     */
    public function getUpdatedAtFormatted()
    {
        return $this->updatedAt ? $this->updatedAt->format('d/m/Y') : null;
    }
}
