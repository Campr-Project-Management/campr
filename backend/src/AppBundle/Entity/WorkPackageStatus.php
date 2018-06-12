<?php

namespace AppBundle\Entity;

use Component\Resource\Model\CodeAwareInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * WorkPackageStatus.
 *
 * @ORM\Table(name="work_package_status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkPackageStatusRepository")
 * @UniqueEntity(fields="code", message="unique.code")
 */
class WorkPackageStatus implements CodeAwareInterface
{
    const OPEN = 1;
    const PENDING = 2;
    const ONGOING = 3;
    const COMPLETED = 4;
    const CLOSED = 5;

    const CODE_OPEN = 'open';
    const CODE_PENDING = 'pending';
    const CODE_ONGOING = 'ongoing';
    const CODE_COMPLETED = 'completed';
    const CODE_CLOSED = 'closed';

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
     * @ORM\Column(name="visible", type="boolean", nullable=false, options={"default"=1})
     */
    private $visible;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="workPackageStatus")
     */
    private $workPackages;

    /**
     * @var Project
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="workPackageStatuses")
     * @ORM\JoinColumn(name="project_id", nullable=true)
     */
    private $project;

    /**
     * @var int
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="progress", type="integer", options={"default":-1})
     */
    private $progress = -1;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false, unique=true)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(name="default", type="boolean", nullable=false, options={"default"=0})
     */
    private $default;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->workPackages = new ArrayCollection();
        $this->visible = true;
        $this->default = false;
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return WorkPackageStatus
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     *
     * @return WorkPackageStatus
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param bool $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * Add workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function addWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages[] = $workPackage;

        return $this;
    }

    /**
     * Remove workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageStatus
     */
    public function removeWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages->removeElement($workPackage);

        return $this;
    }

    /**
     * @return WorkPackage[]|ArrayCollection
     */
    public function getWorkPackages()
    {
        return $this->workPackages;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return WorkPackageStatus
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return WorkPackageStatus
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
     * @return WorkPackageStatus
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
     * Returns Project id.
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
     * Returns Project name.
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
     * @return int
     */
    public function getProgress(): int
    {
        return (int) $this->progress;
    }

    /**
     * @param int $progress
     */
    public function setProgress(int $progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return bool
     */
    public function isClosed(): bool
    {
        return self::CLOSED === $this->getId();
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return self::OPEN === $this->getId();
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return self::PENDING === $this->getId();
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return self::COMPLETED === $this->getId();
    }

    /**
     * @return bool
     */
    public function isOnGoing(): bool
    {
        return self::ONGOING === $this->getId();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string|null $code
     */
    public function setCode(string $code = null)
    {
        $this->code = $code;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault(bool $default = null)
    {
        $this->default = (bool) $default;
    }
}
