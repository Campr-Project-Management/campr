<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WorkPackageProjectWorkCostType.
 *
 * @ORM\Table(name="work_package_project_work_cost_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkPackageProjectWorkCostTypeRepository")
 */
class WorkPackageProjectWorkCostType
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
     * @ORM\Column(name="name", type="string", length=255, options={"default": "Resource"})
     */
    private $name = 'Resource';

    /**
     * @var WorkPackage
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumn(name="work_package_id", nullable=true)
     */
    private $workPackage;

    /**
     * @var ProjectWorkCostType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectWorkCostType")
     * @ORM\JoinColumn(name="project_work_cost_type_id", nullable=true)
     */
    private $projectWorkCostType;

    /**
     * @var float
     *
     * @ORM\Column(name="base", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $base;

    /**
     * @var float
     *
     * @ORM\Column(name="change_value", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $change;

    /**
     * @var float
     *
     * @ORM\Column(name="actual", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $actual;

    /**
     * @var float
     *
     * @ORM\Column(name="remaining", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $remaining;

    /**
     * @var float
     *
     * @ORM\Column(name="forecast", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $forecast;

    /**
     * @var Calendar
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Calendar")
     * @ORM\JoinColumn(name="calendar_id", referencedColumnName="id")
     */
    private $calendar;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_generic", type="boolean", nullable=false, options={"default"=0})
     */
    private $isGeneric = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_inactive", type="boolean", nullable=false, options={"default"=0})
     */
    private $isInactive = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enterprise", type="boolean", nullable=false, options={"default"=0})
     */
    private $isEnterprise = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_cost_resource", type="boolean", nullable=false, options={"default"=0})
     */
    private $isCostResource = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_budget", type="boolean", nullable=false, options={"default"=0})
     */
    private $isBudget = false;

    /**
     * @var ArrayCollection|Assignment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Assignment", mappedBy="workPackageProjectWorkCostType")
     */
    private $assignments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->assignments = new ArrayCollection();
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
     * Set base.
     *
     * @param string $base
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base.
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set change.
     *
     * @param string $change
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setChange($change)
    {
        $this->change = $change;

        return $this;
    }

    /**
     * Get change.
     *
     * @return string
     */
    public function getChange()
    {
        return $this->change;
    }

    /**
     * Set actual.
     *
     * @param string $actual
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setActual($actual)
    {
        $this->actual = $actual;

        return $this;
    }

    /**
     * Get actual.
     *
     * @return string
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Set remaining.
     *
     * @param string $remaining
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setRemaining($remaining)
    {
        $this->remaining = $remaining;

        return $this;
    }

    /**
     * Get remaining.
     *
     * @return string
     */
    public function getRemaining()
    {
        return $this->remaining;
    }

    /**
     * Set forecast.
     *
     * @param string $forecast
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;

        return $this;
    }

    /**
     * Get forecast.
     *
     * @return string
     */
    public function getForecast()
    {
        return $this->forecast;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setCreatedAt(\DateTime $createdAt = null)
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
     * @return WorkPackageProjectWorkCostType
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
     * Set workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackage = $workPackage;

        return $this;
    }

    /**
     * Get workPackage.
     *
     * @return WorkPackage
     */
    public function getWorkPackage()
    {
        return $this->workPackage;
    }

    /**
     * Set projectWorkCostType.
     *
     * @param ProjectWorkCostType $projectWorkCostType
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setProjectWorkCostType(ProjectWorkCostType $projectWorkCostType)
    {
        $this->projectWorkCostType = $projectWorkCostType;

        return $this;
    }

    /**
     * Get projectWorkCostType.
     *
     * @return ProjectWorkCostType
     */
    public function getProjectWorkCostType()
    {
        return $this->projectWorkCostType;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return WorkPackageProjectWorkCostType
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
     * Set isGeneric.
     *
     * @param bool $isGeneric
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setIsGeneric($isGeneric)
    {
        $this->isGeneric = $isGeneric;

        return $this;
    }

    /**
     * Get isGeneric.
     *
     * @return bool
     */
    public function getIsGeneric()
    {
        return $this->isGeneric;
    }

    /**
     * Set isInactive.
     *
     * @param bool $isInactive
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setIsInactive($isInactive)
    {
        $this->isInactive = $isInactive;

        return $this;
    }

    /**
     * Get isInactive.
     *
     * @return bool
     */
    public function getIsInactive()
    {
        return $this->isInactive;
    }

    /**
     * Set isEnterprise.
     *
     * @param bool $isEnterprise
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setIsEnterprise($isEnterprise)
    {
        $this->isEnterprise = $isEnterprise;

        return $this;
    }

    /**
     * Get isEnterprise.
     *
     * @return bool
     */
    public function getIsEnterprise()
    {
        return $this->isEnterprise;
    }

    /**
     * Set isCostResource.
     *
     * @param bool $isCostResource
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setIsCostResource($isCostResource)
    {
        $this->isCostResource = $isCostResource;

        return $this;
    }

    /**
     * Get isCostResource.
     *
     * @return bool
     */
    public function getIsCostResource()
    {
        return $this->isCostResource;
    }

    /**
     * Set isBudget.
     *
     * @param bool $isBudget
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setIsBudget($isBudget)
    {
        $this->isBudget = $isBudget;

        return $this;
    }

    /**
     * Get isBudget.
     *
     * @return bool
     */
    public function getIsBudget()
    {
        return $this->isBudget;
    }

    /**
     * Set calendar.
     *
     * @param Calendar $calendar
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setCalendar(Calendar $calendar = null)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar.
     *
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * Add assignment.
     *
     * @param Assignment $assignment
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function addAssignment(Assignment $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment.
     *
     * @param Assignment $assignment
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function removeAssignment(Assignment $assignment)
    {
        $this->assignments->removeElement($assignment);

        return $this;
    }

    /**
     * Get assignments.
     *
     * @return ArrayCollection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}
