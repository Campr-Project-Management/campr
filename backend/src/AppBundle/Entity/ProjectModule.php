<?php

namespace AppBundle\Entity;

use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProjectModule.
 *
 * @ORM\Table(name="project_module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectModuleRepository")
 */
class ProjectModule implements ProjectAwareInterface, ResourceInterface, CloneableInterface, TimestampableInterface
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
     * @var Project
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="projectModules", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", onDelete="CASCADE")
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
     * @ORM\Column(name="is_enabled", type="boolean")
     */
    private $isEnabled;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_required", type="boolean", nullable=true)
     */
    private $isRequired;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_print", type="boolean", nullable=true)
     */
    private $isPrint;

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
     * ProjectModule constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isEnabled = false;
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
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return ProjectModule
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
}
