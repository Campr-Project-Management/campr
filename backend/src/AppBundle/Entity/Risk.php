<?php

namespace AppBundle\Entity;

use Component\Project\ProjectAwareInterface;
use Component\Project\ProjectInterface;
use Component\TimeUnit\TimeUnitAwareInterface;
use Component\TimeUnit\TimeUnitsConvertor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Risk.
 *
 * @ORM\Table(name="risk")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RiskRepository")
 */
class Risk implements TimeUnitAwareInterface, ProjectAwareInterface
{
    const PRIORITY_VERY_LOW = 0;
    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;
    const PRIORITY_VERY_HIGH = 4;

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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="risks")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="impact", type="integer")
     */
    private $impact;

    /**
     * @var int
     *
     * @ORM\Column(name="probability", type="integer")
     */
    private $probability;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="decimal", precision=25, scale=2, nullable=true)
     */
    private $cost;

    /**
     * @var string
     *
     * @ORM\Column(name="delay", type="decimal", precision=9, scale=2, nullable=true)
     */
    private $delay;

    /**
     * @var string
     *
     * @ORM\Column(name="delay_unit", type="string", length=255)
     */
    private $delayUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;

    /**
     * @var RiskStrategy|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RiskStrategy")
     * @ORM\JoinColumn(name="risk_strategy_id")
     */
    private $riskStrategy;

    /**
     * @var ArrayCollection|Measure[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Measure", mappedBy="risk", cascade={"persist"})
     */
    private $measures;

    /**
     * @var RiskCategory|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RiskCategory")
     * @ORM\JoinColumn(name="risk_category_id")
     */
    private $riskCategory;

    /**
     * @var User|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id")
     */
    private $responsibility;

    /**
     * @var User|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by")
     */
    private $createdBy;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var RiskStatus|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RiskStatus")
     * @ORM\JoinColumn(name="risk_status_id")
     */
    private $riskStatus;

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
     * Risk constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->measures = new ArrayCollection();
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
     * Set project.
     *
     * @param ProjectInterface $project
     *
     * @return Risk
     */
    public function setProject(ProjectInterface $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return ProjectInterface
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
     * Set title.
     *
     * @param string $title
     *
     * @return Risk
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Risk
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
     * Set cost.
     *
     * @param string $cost
     *
     * @return Risk
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost.
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set delay.
     *
     * @param string $delay
     *
     * @return Risk
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;

        return $this;
    }

    /**
     * Get delay.
     *
     * @return string
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Set priority.
     *
     * @param string $priority
     *
     * @return Risk
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set impact.
     *
     * @param int $impact
     *
     * @return Risk
     */
    public function setImpact($impact)
    {
        $this->impact = $impact;

        return $this;
    }

    /**
     * Get impact.
     *
     * @return int
     */
    public function getImpact()
    {
        return $this->impact;
    }

    /**
     * Set probability.
     *
     * @param int $probability
     *
     * @return Risk
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;

        return $this;
    }

    /**
     * Get probability.
     *
     * @return string
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * Set dueDate.
     *
     * @param \DateTime $dueDate
     *
     * @return Risk
     */
    public function setDueDate(\DateTime $dueDate = null)
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    /**
     * Get dueDate.
     *
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Risk
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
     * @return Risk
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
     * Set riskStrategy.
     *
     * @param RiskStrategy $riskStrategy
     *
     * @return Risk
     */
    public function setRiskStrategy(RiskStrategy $riskStrategy = null)
    {
        $this->riskStrategy = $riskStrategy;

        return $this;
    }

    /**
     * Get riskStrategy.
     *
     * @return RiskStrategy
     */
    public function getRiskStrategy()
    {
        return $this->riskStrategy;
    }

    /**
     * Returns riskStrategy id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("riskStrategy")
     *
     * @return string
     */
    public function getRiskStrategyId()
    {
        return $this->riskStrategy ? $this->riskStrategy->getId() : null;
    }

    /**
     * Returns riskStrategy name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("riskStrategyName")
     *
     * @return string
     */
    public function getRiskStrategyName()
    {
        return $this->riskStrategy ? $this->riskStrategy->getName() : null;
    }

    /**
     * Set riskCategory.
     *
     * @param RiskCategory $riskCategory
     *
     * @return Risk
     */
    public function setRiskCategory(RiskCategory $riskCategory = null)
    {
        $this->riskCategory = $riskCategory;

        return $this;
    }

    /**
     * Get riskCategory.
     *
     * @return RiskCategory
     */
    public function getRiskCategory()
    {
        return $this->riskCategory;
    }

    /**
     * Returns riskCategory id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("riskCategory")
     *
     * @return string
     */
    public function getRiskCategoryId()
    {
        return $this->riskCategory ? $this->riskCategory->getId() : null;
    }

    /**
     * Returns riskCategory name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("riskCategoryName")
     *
     * @return string
     */
    public function getRiskCategoryName()
    {
        return $this->riskCategory ? $this->riskCategory->getName() : null;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return Risk
     */
    public function setResponsibility(User $responsibility = null)
    {
        $this->responsibility = $responsibility;

        return $this;
    }

    /**
     * Get responsibility.
     *
     * @return User
     */
    public function getResponsibility()
    {
        return $this->responsibility;
    }

    /**
     * Returns responsibility id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibility")
     *
     * @return string
     */
    public function getResponsibilityId()
    {
        return $this->responsibility ? $this->responsibility->getId() : null;
    }

    /**
     * Returns responsibility full name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityFullName")
     *
     * @return string
     */
    public function getResponsibilityFullName()
    {
        return $this->responsibility ? $this->responsibility->getFullName() : null;
    }

    /**
     * Set status.
     *
     * @param RiskStatus $riskStatus
     *
     * @return Risk
     */
    public function setRiskStatus(RiskStatus $riskStatus = null)
    {
        $this->riskStatus = $riskStatus;

        return $this;
    }

    /**
     * Get status.
     *
     * @return RiskStatus|null
     */
    public function getRiskStatus()
    {
        return $this->riskStatus;
    }

    /**
     * Returns status id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("status")
     *
     * @return string
     */
    public function getStatusId()
    {
        return $this->riskStatus ? $this->riskStatus->getId() : null;
    }

    /**
     * Returns status name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("statusName")
     *
     * @return string
     */
    public function getStatusName()
    {
        return $this->riskStatus ? $this->riskStatus->getName() : null;
    }

    /**
     * @param Measure $measure
     *
     * @return Risk
     */
    public function addMeasure(Measure $measure)
    {
        $measure->setRisk($this);
        $this->measures[] = $measure;

        return $this;
    }

    /**
     * @param Measure $measure
     *
     * @return Risk
     */
    public function removeMeasure(Measure $measure)
    {
        $this->measures->removeElement($measure);

        return $this;
    }

    /**
     * @return Measure[]|ArrayCollection
     */
    public function getMeasures()
    {
        return $this->measures;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return float
     */
    public function getMeasuresTotalCost()
    {
        $cost = 0;
        foreach ($this->getMeasures() as $measure) {
            $cost += $measure->getCost();
        }

        return round($cost, 2);
    }

    /**
     * @return string
     */
    public function getDelayUnit()
    {
        return $this->delayUnit;
    }

    /**
     * @param $delayUnit
     *
     * @return $this
     */
    public function setDelayUnit(string $delayUnit = null)
    {
        $this->delayUnit = $delayUnit;

        return $this;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return Risk
     */
    public function setCreatedBy(User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Returns createdBy id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdBy")
     *
     * @return string
     */
    public function getCreatedById()
    {
        return $this->createdBy ? $this->createdBy->getId() : null;
    }

    /**
     * Returns createdBy full name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("createdByFullName")
     *
     * @return string
     */
    public function getCreatedByFullName()
    {
        return $this->createdBy ? $this->createdBy->getFullName() : null;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return float
     */
    public function getPotentialCost(): float
    {
        return round(($this->getProbability() / 100) * $this->getCost(), 4);
    }

    /**
     * @return string
     */
    public function getTimeUnit(): string
    {
        return (string) $this->getDelayUnit();
    }

    /**
     * @param string $timeUnit
     */
    public function setTimeUnit(string $timeUnit = null)
    {
        $this->setDelay($timeUnit);
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return float
     */
    public function getPotentialDelay(): float
    {
        return round(($this->getProbability() / 100) * $this->getDelay(), 4);
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return float
     */
    public function getPotentialDelayHours(): float
    {
        $amount = $this->getPotentialDelay();
        if (empty($amount)) {
            return $amount;
        }

        $convertor = new TimeUnitsConvertor($this);

        return round($convertor->toHours($amount), 4);
    }
}
