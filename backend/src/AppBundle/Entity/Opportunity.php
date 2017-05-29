<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Opportunity.
 *
 * @ORM\Table(name="opportunity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OpportunityRepository")
 */
class Opportunity
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="opportunities")
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
     * @ORM\Column(name="cost_savings", type="string", length=255, nullable=true)
     */
    private $costSavings;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=255)
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="budget", type="string", length=255, nullable=true)
     */
    private $budget;

    /**
     * @var string
     *
     * @ORM\Column(name="time_savings", type="string", length=255, nullable=true)
     */
    private $timeSavings;

    /**
     * @var string
     *
     * @ORM\Column(name="time_unit", type="string", length=255)
     */
    private $timeUnit;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255, nullable=false)
     */
    private $priority;

    /**
     * @var OpportunityStrategy|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OpportunityStrategy")
     * @ORM\JoinColumn(name="opportunity_strategy_id")
     */
    private $opportunityStrategy;

    /**
     * @var ArrayCollection|Measure[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Measure", mappedBy="opportunity")
     */
    private $measures;

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
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var OpportunityStatus|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\OpportunityStatus")
     * @ORM\JoinColumn(name="opportunity_status_id")
     */
    private $opportunityStatus;

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
     * @param Project $project
     *
     * @return Opportunity
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
     * @return Opportunity
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
     * @return Opportunity
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
     * Set costSavings.
     *
     * @param string $costSavings
     *
     * @return Opportunity
     */
    public function setCostSavings($costSavings)
    {
        $this->costSavings = $costSavings;

        return $this;
    }

    /**
     * Get costSavings.
     *
     * @return string
     */
    public function getCostSavings()
    {
        return $this->costSavings;
    }

    /**
     * Set budget.
     *
     * @param string $budget
     *
     * @return Opportunity
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget.
     *
     * @return string
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set timeSavings.
     *
     * @param string $timeSavings
     *
     * @return Opportunity
     */
    public function setTimeSavings($timeSavings)
    {
        $this->timeSavings = $timeSavings;

        return $this;
    }

    /**
     * Get timeSavings.
     *
     * @return string
     */
    public function getTimeSavings()
    {
        return $this->timeSavings;
    }

    /**
     * Set priority.
     *
     * @param string $priority
     *
     * @return Opportunity
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
     * @return Opportunity
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
     * @return Opportunity
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
     * @return Opportunity
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
     * @return Opportunity
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
     * @return Opportunity
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
     * Set opportunityStrategy.
     *
     * @param OpportunityStrategy $opportunityStrategy
     *
     * @return Risk
     */
    public function setOpportunityStrategy(OpportunityStrategy $opportunityStrategy = null)
    {
        $this->opportunityStrategy = $opportunityStrategy;

        return $this;
    }

    /**
     * Get opportunityStrategy.
     *
     * @return OpportunityStrategy
     */
    public function getOpportunityStrategy()
    {
        return $this->opportunityStrategy;
    }

    /**
     * Returns opportunityStrategy id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("opportunityStrategy")
     *
     * @return string
     */
    public function getOpportunityStrategyId()
    {
        return $this->opportunityStrategy ? $this->opportunityStrategy->getId() : null;
    }

    /**
     * Returns opportunityStrategy name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("opportunityStrategyName")
     *
     * @return string
     */
    public function getOpportunityStrategyName()
    {
        return $this->opportunityStrategy ? $this->opportunityStrategy->getName() : null;
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
     * Set opportunityStatus.
     *
     * @param OpportunityStatus $opportunityStatus
     *
     * @return Opportunity
     */
    public function setOpportunityStatus(OpportunityStatus $opportunityStatus)
    {
        $this->opportunityStatus = $opportunityStatus;

        return $this;
    }

    /**
     * Get opportunityStatus.
     *
     * @return OpportunityStatus
     */
    public function getOpportunityStatus()
    {
        return $this->opportunityStatus;
    }

    /**
     * Returns opportunityStatus id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("opportunityStatus")
     *
     * @return string
     */
    public function getOpportunityStatusId()
    {
        return $this->opportunityStatus ? $this->opportunityStatus->getId() : null;
    }

    /**
     * Returns opportunityStatus name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("opportunityStatusName")
     *
     * @return string
     */
    public function getOpportunityStatusName()
    {
        return $this->opportunityStatus ? $this->opportunityStatus->getName() : null;
    }

    /**
     * @param Measure $measure
     *
     * @return Opportunity
     */
    public function addMeasure(Measure $measure)
    {
        $this->measures[] = $measure;

        return $this;
    }

    /**
     * @param Measure $measure
     *
     * @return Opportunity
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
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeUnit()
    {
        return $this->timeUnit;
    }

    /**
     * @param string $timeUnit
     */
    public function setTimeUnit($timeUnit)
    {
        $this->timeUnit = $timeUnit;

        return $this;
    }
}
