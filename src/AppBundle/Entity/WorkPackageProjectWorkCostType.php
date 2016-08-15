<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var WorkPackage
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumn(name="work_package_id", nullable=false)
     */
    private $workPackage;

    /**
     * @var ProjectWorkCostType
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectWorkCostType")
     * @ORM\JoinColumn(name="project_work_cost_type_id", nullable=false)
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
     * @ORM\Column(name="change", type="decimal", precision=10, scale=2, nullable=true)
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
     * @return WorkPackageProjectWorkCostType
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
     * Set workPackage.
     *
     * @param \AppBundle\Entity\WorkPackage $workPackage
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setWorkPackage(\AppBundle\Entity\WorkPackage $workPackage)
    {
        $this->workPackage = $workPackage;

        return $this;
    }

    /**
     * Get workPackage.
     *
     * @return \AppBundle\Entity\WorkPackage
     */
    public function getWorkPackage()
    {
        return $this->workPackage;
    }

    /**
     * Set projectWorkCostType.
     *
     * @param \AppBundle\Entity\ProjectWorkCostType $projectWorkCostType
     *
     * @return WorkPackageProjectWorkCostType
     */
    public function setProjectWorkCostType(\AppBundle\Entity\ProjectWorkCostType $projectWorkCostType)
    {
        $this->projectWorkCostType = $projectWorkCostType;

        return $this;
    }

    /**
     * Get projectWorkCostType.
     *
     * @return \AppBundle\Entity\ProjectWorkCostType
     */
    public function getProjectWorkCostType()
    {
        return $this->projectWorkCostType;
    }
}
