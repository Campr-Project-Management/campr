<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project.
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=128, unique=true)
     */
    private $number;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sponsor_id", referencedColumnName="id")
     * })
     */
    private $sponsor;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     * })
     */
    private $manager;

    /**
     * @var Company
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     * })
     */
    private $company;

    /**
     * @var ProjectComplexity
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectComplexity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_complexity_id", referencedColumnName="id")
     * })
     */
    private $projectComplexity;

    /**
     * @var ProjectCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_category_id", referencedColumnName="id")
     * })
     */
    private $projectCategory;

    /**
     * @var ProjectScope
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectScope")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_scope_id", referencedColumnName="id")
     * })
     */
    private $projectScope;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var Portfolio
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id")
     * })
     */
    private $portfolio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="status_updated_at", type="datetime", nullable=true)
     */
    private $statusUpdatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_at", type="datetime")
     */
    private $approvedAt;

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
     * Set name.
     *
     * @param string $name
     *
     * @return Project
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
     * Set number.
     *
     * @param string $number
     *
     * @return Project
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set statusUpdatedAt.
     *
     * @param \DateTime $statusUpdatedAt
     *
     * @return Project
     */
    public function setStatusUpdatedAt($statusUpdatedAt)
    {
        $this->statusUpdatedAt = $statusUpdatedAt;

        return $this;
    }

    /**
     * Get statusUpdatedAt.
     *
     * @return \DateTime
     */
    public function getStatusUpdatedAt()
    {
        return $this->statusUpdatedAt;
    }

    /**
     * Set approvedAt.
     *
     * @param \DateTime $approvedAt
     *
     * @return Project
     */
    public function setApprovedAt($approvedAt)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    /**
     * Get approvedAt.
     *
     * @return \DateTime
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Project
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
     * @return Project
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
     * Set sponsor.
     *
     * @param \AppBundle\Entity\User $sponsor
     *
     * @return Project
     */
    public function setSponsor(\AppBundle\Entity\User $sponsor = null)
    {
        $this->sponsor = $sponsor;

        return $this;
    }

    /**
     * Get sponsor.
     *
     * @return \AppBundle\Entity\User
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set manager.
     *
     * @param \AppBundle\Entity\User $manager
     *
     * @return Project
     */
    public function setManager(\AppBundle\Entity\User $manager = null)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager.
     *
     * @return \AppBundle\Entity\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set company.
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return Project
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set projectComplexity.
     *
     * @param \AppBundle\Entity\ProjectComplexity $projectComplexity
     *
     * @return Project
     */
    public function setProjectComplexity(\AppBundle\Entity\ProjectComplexity $projectComplexity = null)
    {
        $this->projectComplexity = $projectComplexity;

        return $this;
    }

    /**
     * Get projectComplexity.
     *
     * @return \AppBundle\Entity\ProjectComplexity
     */
    public function getProjectComplexity()
    {
        return $this->projectComplexity;
    }

    /**
     * Set projectCategory.
     *
     * @param \AppBundle\Entity\ProjectCategory $projectCategory
     *
     * @return Project
     */
    public function setProjectCategory(\AppBundle\Entity\ProjectCategory $projectCategory = null)
    {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory.
     *
     * @return \AppBundle\Entity\ProjectCategory
     */
    public function getProjectCategory()
    {
        return $this->projectCategory;
    }

    /**
     * Set projectScope.
     *
     * @param \AppBundle\Entity\ProjectScope $projectScope
     *
     * @return Project
     */
    public function setProjectScope(\AppBundle\Entity\ProjectScope $projectScope = null)
    {
        $this->projectScope = $projectScope;

        return $this;
    }

    /**
     * Get projectScope.
     *
     * @return \AppBundle\Entity\ProjectScope
     */
    public function getProjectScope()
    {
        return $this->projectScope;
    }

    /**
     * Set status.
     *
     * @param \AppBundle\Entity\ProjectStatus $status
     *
     * @return Project
     */
    public function setStatus(\AppBundle\Entity\ProjectStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \AppBundle\Entity\ProjectStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set portfolio.
     *
     * @param \AppBundle\Entity\Portfolio $portfolio
     *
     * @return Project
     */
    public function setPortfolio(\AppBundle\Entity\Portfolio $portfolio = null)
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    /**
     * Get portfolio.
     *
     * @return \AppBundle\Entity\Portfolio
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }
}
