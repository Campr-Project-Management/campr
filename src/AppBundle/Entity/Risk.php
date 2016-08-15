<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Risk.
 *
 * @ORM\Table(name="risk")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RiskRepository")
 */
class Risk
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
     * @var Impact
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Impact")
     * @ORM\JoinColumn(name="impact_id")
     */
    private $impact;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="string", length=255)
     */
    private $cost;

    /**
     * @var string
     *
     * @ORM\Column(name="budget", type="string", length=255)
     */
    private $budget;

    /**
     * @var string
     *
     * @ORM\Column(name="delay", type="string", length=255)
     */
    private $delay;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;

    /**
     * @var RiskStrategy|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RiskStrategy")
     * @ORM\JoinColumn(name="risk_strategy_id")
     */
    private $riskStrategy;

    /**
     * @var string
     *
     * @ORM\Column(name="measure", type="text")
     */
    private $measure;

    /**
     * @var RiskCategory|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\RiskCategory")
     * @ORM\JoinColumn(name="risk_category_id")
     */
    private $riskCategory;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id")
     */
    private $responsibility;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var Status|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumn(name="status_id")
     */
    private $status;

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
     * Set budget.
     *
     * @param string $budget
     *
     * @return Risk
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
     * Set measure.
     *
     * @param string $measure
     *
     * @return Risk
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;

        return $this;
    }

    /**
     * Get measure.
     *
     * @return string
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * Set dueDate.
     *
     * @param \DateTime $dueDate
     *
     * @return Risk
     */
    public function setDueDate($dueDate)
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
    public function setCreatedAt($createdAt)
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
    public function setUpdatedAt($updatedAt)
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
     * Set impact.
     *
     * @param \AppBundle\Entity\Impact $impact
     *
     * @return Risk
     */
    public function setImpact(\AppBundle\Entity\Impact $impact = null)
    {
        $this->impact = $impact;

        return $this;
    }

    /**
     * Get impact.
     *
     * @return \AppBundle\Entity\Impact
     */
    public function getImpact()
    {
        return $this->impact;
    }

    /**
     * Set riskStrategy.
     *
     * @param \AppBundle\Entity\RiskStrategy $riskStrategy
     *
     * @return Risk
     */
    public function setRiskStrategy(\AppBundle\Entity\RiskStrategy $riskStrategy = null)
    {
        $this->riskStrategy = $riskStrategy;

        return $this;
    }

    /**
     * Get riskStrategy.
     *
     * @return \AppBundle\Entity\RiskStrategy
     */
    public function getRiskStrategy()
    {
        return $this->riskStrategy;
    }

    /**
     * Set riskCategory.
     *
     * @param \AppBundle\Entity\RiskCategory $riskCategory
     *
     * @return Risk
     */
    public function setRiskCategory(\AppBundle\Entity\RiskCategory $riskCategory = null)
    {
        $this->riskCategory = $riskCategory;

        return $this;
    }

    /**
     * Get riskCategory.
     *
     * @return \AppBundle\Entity\RiskCategory
     */
    public function getRiskCategory()
    {
        return $this->riskCategory;
    }

    /**
     * Set responsibility.
     *
     * @param \AppBundle\Entity\User $responsibility
     *
     * @return Risk
     */
    public function setResponsibility(\AppBundle\Entity\User $responsibility = null)
    {
        $this->responsibility = $responsibility;

        return $this;
    }

    /**
     * Get responsibility.
     *
     * @return \AppBundle\Entity\User
     */
    public function getResponsibility()
    {
        return $this->responsibility;
    }

    /**
     * Set status.
     *
     * @param \AppBundle\Entity\Status $status
     *
     * @return Risk
     */
    public function setStatus(\AppBundle\Entity\Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \AppBundle\Entity\Status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
