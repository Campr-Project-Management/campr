<?php

namespace AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Project.
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 * @UniqueEntity(fields="number", message="unique.number")
 * @Vich\Uploadable
 */
class Project
{
    const STATUS_RED = 0;
    const STATUS_YELLOW = 1;
    const STATUS_GREEN = 2;

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
     * @var string
     * @ORM\Column(name="short_note", type="text", nullable=true)
     */
    private $shortNote;

    /**
     * @var Company
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $company;

    /**
     * @var ProjectComplexity
     *
     * @Serializer\Exclude()
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
     * @Serializer\Exclude()
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
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectScope")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_scope_id", referencedColumnName="id")
     * })
     */
    private $projectScope;

    /**
     * @var ProjectStatus
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjectStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="project_status_id", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var ArrayCollection|ProjectUser[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectUser", mappedBy="project", orphanRemoval=true)
     */
    private $projectUsers;

    /**
     * @var ArrayCollection|ProjectTeam[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectTeam", mappedBy="project")
     */
    private $projectTeams;

    /**
     * @var Portfolio
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolio_id", referencedColumnName="id")
     * })
     */
    private $portfolio;

    /**
     * @var ArrayCollection|Calendar[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Calendar", mappedBy="project")
     */
    private $calendars;

    /**
     * @var ArrayCollection|Info[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Info", mappedBy="project")
     */
    private $infos;

    /**
     * @var ArrayCollection|Todo[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="project")
     */
    private $todos;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="project", cascade={"all"})
     */
    private $distributionLists;

    /**
     * @var ArrayCollection|ChatRoom[]
     *
     * @Serializer\Exclude()
     *
     ** @ORM\OneToMany(targetEntity="AppBundle\Entity\ChatRoom", mappedBy="project")
     */
    private $chatRooms;

    /**
     * @var ArrayCollection|Message[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="project")
     */
    private $messages;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="project")
     */
    private $workPackages;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="status_updated_at", type="datetime", nullable=true)
     */
    private $statusUpdatedAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * @Vich\UploadableField(mapping="project_images", fileNameProperty="logo")
     * @Serializer\Exclude()
     *
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     *
     * @Serializer\Exclude()
     *
     * @var string
     */
    private $logo;

    /**
     * @var ArrayCollection|FileSystem[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\FileSystem", mappedBy="project")
     */
    private $fileSystems;

    /**
     * @var ArrayCollection|Label[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Label", mappedBy="project")
     */
    private $labels;

    /**
     * @var null|Label
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Label")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="label_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     * @Serializer\Exclude()
     */
    private $label;

    /**
     * @var ArrayCollection|Message[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Meeting", mappedBy="project")
     */
    private $meetings;

    /**
     * @var Cost[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cost", mappedBy="project")
     */
    private $costs;

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
     * @var ArrayCollection|Contract[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="project")
     */
    private $contracts;

    /**
     * @var ArrayCollection|User[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User", inversedBy="favoriteProjects")
     * @ORM\JoinTable(
     *     name="project_user_favorites",
     *     joinColumns={
     *         @ORM\JoinColumn(name="project_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id")
     *     }
     * )
     */
    private $userFavorites;

    /**
     * @var Programme|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Programme", inversedBy="projects")
     * @ORM\JoinColumn(name="programme_id")
     */
    private $programme;

    /**
     * @var ArrayCollection|ProjectObjective[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectObjective", mappedBy="project")
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $projectObjectives;

    /**
     * @var ArrayCollection|ProjectLimitation[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectLimitation", mappedBy="project")
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $projectLimitations;

    /**
     * @var ArrayCollection|ProjectDeliverable[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectDeliverable", mappedBy="project")
     * @ORM\OrderBy({"sequence" = "ASC"})
     */
    private $projectDeliverables;

    /**
     * @var array
     *
     * @ORM\Column(name="configuration", type="json_array", nullable=true)
     */
    private $configuration;

    /**
     * @var ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectModule", mappedBy="project", cascade={"persist"})
     */
    private $projectModules;

    /**
     * @var ArrayCollection|WorkPackageStatus[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackageStatus", mappedBy="project")
     */
    private $workPackageStatuses;

    /**
     * @var ArrayCollection|Unit[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Unit", mappedBy="project")
     */
    private $units;

    /**
     * @var ArrayCollection|resource[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Resource", mappedBy="project")
     */
    private $resources;

    /**
     * @var Subteam[]|ArrayCollection
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Subteam", mappedBy="project", orphanRemoval=true, cascade={"all"})
     */
    private $subteams;

    /**
     * @var ArrayCollection|ProjectTeam[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Risk", mappedBy="project")
     */
    private $risks;

    /**
     * @var ArrayCollection|ProjectTeam[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Opportunity", mappedBy="project")
     */
    private $opportunities;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Decision", mappedBy="project")
     */
    private $decisions;

    /**
     * @var ArrayCollection|OpportunityStatus[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OpportunityStatus", mappedBy="project")
     */
    private $opportunityStatuses;

    /**
     * @var ArrayCollection|OpportunityStrategy[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OpportunityStrategy", mappedBy="project")
     */
    private $opportunityStrategies;

    /**
     * @var ArrayCollection|Status[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Status", mappedBy="project")
     */
    private $riskStatuses;

    /**
     * @var ArrayCollection|RiskStrategy[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\RiskStrategy", mappedBy="project")
     */
    private $riskStrategies;

    /**
     * @var ArrayCollection|RiskStrategy[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectDepartment", mappedBy="project")
     */
    private $projectDepartments;

    /**
     * @var ColorStatus
     *
     * @Serializer\Exclude()
     */
    private $colorStatus;

    /**
     * @var ArrayCollection|StatusReport[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\StatusReport", mappedBy="project")
     */
    private $statusReports;

    /**
     * @var ArrayCollection|StatusReportConfig[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\StatusReportConfig", mappedBy="project")
     */
    private $statusReportConfigs;

    /**
     * @var ArrayCollection|ProjectCloseDown[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectCloseDown", mappedBy="project")
     */
    private $projectCloseDowns;

    /**
     * @var int
     * @ORM\Column(name="progress", type="integer", options={"default"=0})
     */
    private $progress = 0;

    /**
     * @var ArrayCollection|ProjectRole[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectRole", mappedBy="project",  cascade={"all"})
     */
    private $projectRoles;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->calendars = new ArrayCollection();
        $this->workPackages = new ArrayCollection();
        $this->fileSystems = new ArrayCollection();
        $this->chatRooms = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->projectUsers = new ArrayCollection();
        $this->infos = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->distributionLists = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->contracts = new ArrayCollection();
        $this->meetings = new ArrayCollection();
        $this->userFavorites = new ArrayCollection();
        $this->projectTeams = new ArrayCollection();
        $this->projectObjectives = new ArrayCollection();
        $this->projectLimitations = new ArrayCollection();
        $this->projectDeliverables = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->projectModules = new ArrayCollection();
        $this->workPackageStatuses = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->subteams = new ArrayCollection();
        $this->risks = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->opportunityStatuses = new ArrayCollection();
        $this->opportunityStrategies = new ArrayCollection();
        $this->riskStrategies = new ArrayCollection();
        $this->riskStatuses = new ArrayCollection();
        $this->projectDepartments = new ArrayCollection();
        $this->statusReports = new ArrayCollection();
        $this->statusReportConfigs = new ArrayCollection();
        $this->projectCloseDowns = new ArrayCollection();
        $this->projectRoles = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->name;
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
    public function setStatusUpdatedAt(\DateTime $statusUpdatedAt = null)
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
    public function setApprovedAt(\DateTime $approvedAt = null)
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
     * @return Project
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
     * Set company.
     *
     * @param Company $company
     *
     * @return Project
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Returns company id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("company")
     *
     * @return string
     */
    public function getCompanyId()
    {
        return $this->company ? $this->company->getId() : null;
    }

    /**
     * Returns company name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("companyName")
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->company ? $this->company->getName() : null;
    }

    /**
     * Returns the project manager's id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectManager")
     *
     * @return int
     */
    public function getProjectManagerId()
    {
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER)) {
                return $projectUser->getUserId();
            }
        }

        return null;
    }

    /**
     * Returns project manager's name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectManagerName")
     *
     * @return string
     */
    public function getProjectManagerName()
    {
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER)) {
                return $projectUser->__toString();
            }
        }

        return null;
    }

    /**
     * Returns all the project managers.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectManagers")
     *
     * @return string
     */
    public function getProjectManagers()
    {
        $managers = [];
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_MANAGER)) {
                $managers[] = $projectUser->getUser();
            }
        }

        return $managers;
    }

    /**
     * Returns the project sponsor's id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectSponsor")
     *
     * @return int
     */
    public function getProjectSponsorId()
    {
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_SPONSOR)) {
                return $projectUser->getUserId();
            }
        }

        return null;
    }

    /**
     * Returns project sponsor's name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectSponsorName")
     *
     * @return string
     */
    public function getProjectSponsorName()
    {
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_SPONSOR)) {
                return $projectUser->__toString();
            }
        }

        return null;
    }

    /**
     * Returns all the project sponsors.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectSponsors")
     *
     * @return string
     */
    public function getProjectSponsors()
    {
        $sponsors = [];
        foreach ($this->projectUsers as $projectUser) {
            if ($projectUser->hasProjectRole(ProjectRole::ROLE_SPONSOR)) {
                $sponsors[] = $projectUser->getUser();
            }
        }

        return $sponsors;
    }

    /**
     * Set projectComplexity.
     *
     * @param ProjectComplexity $projectComplexity
     *
     * @return Project
     */
    public function setProjectComplexity(ProjectComplexity $projectComplexity = null)
    {
        $this->projectComplexity = $projectComplexity;

        return $this;
    }

    /**
     * Get projectComplexity.
     *
     * @return ProjectComplexity
     */
    public function getProjectComplexity()
    {
        return $this->projectComplexity;
    }

    /**
     * Returns projectComplexity id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectComplexity")
     *
     * @return string
     */
    public function getProjectComplexityId()
    {
        return $this->projectComplexity ? $this->projectComplexity->getId() : null;
    }

    /**
     * Returns projectComplexity name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectComplexityName")
     *
     * @return string
     */
    public function getProjectComplexityName()
    {
        return $this->projectComplexity ? $this->projectComplexity->getName() : null;
    }

    /**
     * Set projectCategory.
     *
     * @param ProjectCategory $projectCategory
     *
     * @return Project
     */
    public function setProjectCategory(ProjectCategory $projectCategory = null)
    {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory.
     *
     * @return ProjectCategory
     */
    public function getProjectCategory()
    {
        return $this->projectCategory;
    }

    /**
     * Returns projectCategory id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCategory")
     *
     * @return string
     */
    public function getProjectCategoryId()
    {
        return $this->projectCategory ? $this->projectCategory->getId() : null;
    }

    /**
     * Returns projectCategory name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectCategoryName")
     *
     * @return string
     */
    public function getProjectCategoryName()
    {
        return $this->projectCategory ? $this->projectCategory->getName() : null;
    }

    /**
     * Set projectScope.
     *
     * @param ProjectScope $projectScope
     *
     * @return Project
     */
    public function setProjectScope(ProjectScope $projectScope = null)
    {
        $this->projectScope = $projectScope;

        return $this;
    }

    /**
     * Get projectScope.
     *
     * @return ProjectScope
     */
    public function getProjectScope()
    {
        return $this->projectScope;
    }

    /**
     * Returns projectScope id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectScope")
     *
     * @return string
     */
    public function getProjectScopeId()
    {
        return $this->projectScope ? $this->projectScope->getId() : null;
    }

    /**
     * Returns projectScope name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectScopeName")
     *
     * @return string
     */
    public function getProjectScopeName()
    {
        return $this->projectScope ? $this->projectScope->getName() : null;
    }

    /**
     * Set status.
     *
     * @param ProjectStatus $status
     *
     * @return Project
     */
    public function setStatus(ProjectStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return ProjectStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set portfolio.
     *
     * @param Portfolio $portfolio
     *
     * @return Project
     */
    public function setPortfolio(Portfolio $portfolio = null)
    {
        $this->portfolio = $portfolio;

        return $this;
    }

    /**
     * Get portfolio.
     *
     * @return Portfolio
     */
    public function getPortfolio()
    {
        return $this->portfolio;
    }

    /**
     * Add calendar.
     *
     * @param Calendar $calendar
     *
     * @return Project
     */
    public function addCalendar(Calendar $calendar)
    {
        $this->calendars[] = $calendar;

        return $this;
    }

    /**
     * Remove calendar.
     *
     * @param Calendar $calendar
     *
     * @return Project
     */
    public function removeCalendar(Calendar $calendar)
    {
        $this->calendars->removeElement($calendar);

        return $this;
    }

    /**
     * Get calendars.
     *
     * @return ArrayCollection
     */
    public function getCalendars()
    {
        return $this->calendars;
    }

    /**
     * Add workPackage.
     *
     * @param WorkPackage $workPackage
     *
     * @return Project
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
     * @return Project
     */
    public function removeWorkPackage(WorkPackage $workPackage)
    {
        $this->workPackages->removeElement($workPackage);

        return $this;
    }

    /**
     * Get workPackages.
     *
     * @return ArrayCollection
     */
    public function getWorkPackages()
    {
        return $this->workPackages;
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
        return $this->status ? $this->status->getId() : null;
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
        return $this->status ? $this->status->getName() : null;
    }

    /**
     * Returns portfolio id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("portfolio")
     *
     * @return string
     */
    public function getPortfolioId()
    {
        return $this->portfolio ? $this->portfolio->getId() : null;
    }

    /**
     * Returns portfolio name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("portfolioName")
     *
     * @return string
     */
    public function getPortfolioName()
    {
        return $this->portfolio ? $this->portfolio->getName() : null;
    }

    /**
     * Add FileSystem.
     *
     * @param FileSystem $fileSystem
     *
     * @return Project
     */
    public function addFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystems[] = $fileSystem;

        return $this;
    }

    /**
     * Add chatRoom.
     *
     * @param ChatRoom $chatRoom
     *
     * @return Project
     */
    public function addChatRoom(ChatRoom $chatRoom)
    {
        $this->chatRooms[] = $chatRoom;

        return $this;
    }

    /**
     * Remove fileSystem.
     *
     * @param FileSystem $fileSystem
     *
     * @return Project
     */
    public function removeFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystems->removeElement($fileSystem);

        return $this;
    }

    /**
     * Remove chatRoom.
     *
     * @param ChatRoom $chatRoom
     *
     * @return Project
     */
    public function removeChatRoom(ChatRoom $chatRoom)
    {
        $this->chatRooms->removeElement($chatRoom);

        return $this;
    }

    /**
     * Get rooms.
     *
     * @return ArrayCollection
     */
    public function getChatRooms()
    {
        return $this->chatRooms;
    }

    /**
     * Add message.
     *
     * @param Message $message
     *
     * @return Project
     */
    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Remove message.
     *
     * @param Message $message
     *
     * @return Project
     */
    public function removeMessage(Message $message)
    {
        $this->messages->removeElement($message);

        return $this;
    }

    /**
     * Get fileSystems.
     *
     * @return ArrayCollection
     */
    public function getFileSystems()
    {
        return $this->fileSystems;
    }

    /**
     * Get messages.
     *
     * @return ArrayCollection
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add projectUser.
     *
     * @param ProjectUser $projectUser
     *
     * @return Project
     */
    public function addProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers[] = $projectUser;

        return $this;
    }

    /**
     * Remove projectUser.
     *
     * @param ProjectUser $projectUser
     */
    public function removeProjectUser(ProjectUser $projectUser)
    {
        $this->projectUsers->removeElement($projectUser);
    }

    /**
     * Get projectUsers.
     *
     * @return ArrayCollection|ProjectUser[]
     */
    public function getProjectUsers()
    {
        return $this->projectUsers;
    }

    /**
     * @param ProjectUser $projectUser
     *
     * @return bool
     */
    public function hasProjectUser(ProjectUser $projectUser)
    {
        return $this->getProjectUsers()->contains($projectUser);
    }

    /**
     * Add Info.
     *
     * @param Info $info
     *
     * @return Project
     */
    public function addInfo(Info $info)
    {
        $this->infos[] = $info;

        return $this;
    }

    /**
     * @param Info $info
     *
     * @return Project
     */
    public function removeInfo(Info $info)
    {
        $this->infos->removeElement($info);

        return $this;
    }

    /**
     * Get infos.
     *
     * @return ArrayCollection|Info[]
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Add todo.
     *
     * @param Todo $todo
     *
     * @return Project
     */
    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;

        return $this;
    }

    /**
     * Remove todo.
     *
     * @param Todo $todo
     */
    public function removeTodo(Todo $todo)
    {
        $this->todos->removeElement($todo);

        return $this;
    }

    /**
     * Get todos.
     *
     * @return ArrayCollection|Todo[]
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * Set logoFile.
     *
     * @param File|null $image
     *
     * @return Project
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    /**
     * Get logoFile.
     *
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * Add distributionList.
     *
     * @param DistributionList $distributionList
     *
     * @return Project
     */
    public function addDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists[] = $distributionList;

        return $this;
    }

    /**
     * Remove distributionList.
     *
     * @param DistributionList $distributionList
     */
    public function removeDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists->removeElement($distributionList);
    }

    /**
     * Get distributionLists.
     *
     * @return ArrayCollection|DistributionList[]
     */
    public function getDistributionLists()
    {
        return $this->distributionLists;
    }

    /**
     * Add label.
     *
     * @param Label $label
     *
     * @return Project
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * Remove label.
     *
     * @param Label $label
     */
    public function removeLabel(Label $label)
    {
        $this->labels->removeElement($label);

        return $this;
    }

    /**
     * Get labels.
     *
     * @return ArrayCollection|Label[]
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param Label|null $label
     *
     * @return Project
     */
    public function setLabel(Label $label = null)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Label|null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("label")
     */
    public function getLabelId()
    {
        return $this->label
            ? $this->label->getId()
            : null
        ;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("labelName")
     */
    public function getLabelName()
    {
        return $this->label
            ? (string) $this->label
            : null
        ;
    }

    /**
     * Add contract.
     *
     * @param Contract $contract
     *
     * @return Project
     */
    public function addContract(Contract $contract)
    {
        $this->contracts[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param Contract $contract
     */
    public function removeContract(Contract $contract)
    {
        $this->contracts->removeElement($contract);
    }

    /**
     * Get contracts.
     *
     * @return ArrayCollection|Contract[]
     */
    public function getContracts()
    {
        return $this->contracts;
    }

    /**
     * Remove meeting.
     *
     * @param Meeting $meeting
     *
     * @return Project
     */
    public function removeMeeting(Meeting $meeting)
    {
        $this->meetings->removeElement($meeting);

        return $this;
    }

    /**
     * Add meeting.
     *
     * @param Meeting $meeting
     *
     * @return Project
     */
    public function addMeeting(Meeting $meeting)
    {
        $this->meetings[] = $meeting;

        return $this;
    }

    /**
     * Get meetings.
     *
     * @return ArrayCollection
     */
    public function getMeetings()
    {
        return $this->meetings;
    }

    /**
     * Add user.
     *
     * @param User $user
     *
     * @return Project
     */
    public function addUserFavorite(User $user)
    {
        if (!$this->userFavorites->contains($user)) {
            $this->userFavorites[] = $user;
        }

        return $this;
    }

    /**
     * Remove user.
     *
     * @param User $user
     *
     * @return Project
     */
    public function removeUserFavorite(User $user)
    {
        if ($this->userFavorites->contains($user)) {
            $this->userFavorites->removeElement($user);
        }

        return $this;
    }

    /**
     * Get users.
     *
     * @return ArrayCollection|User[]
     */
    public function getUserFavorites()
    {
        return $this->userFavorites;
    }

    /**
     * Returns user favorite ids.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFavorites")
     *
     * @return array
     */
    public function getUserFavoritesIds()
    {
        $favorites = [];
        foreach ($this->userFavorites as $userFavorite) {
            $favorites[] = $userFavorite->getId();
        }

        return $favorites;
    }

    /**
     * Add projectTeam.
     *
     * @param ProjectTeam $projectTeam
     *
     * @return Project
     */
    public function addProjectTeam(ProjectTeam $projectTeam)
    {
        $this->projectTeams[] = $projectTeam;

        return $this;
    }

    /**
     * Remove projectTeam.
     *
     * @param ProjectTeam $projectTeam
     *
     * @return Project;
     */
    public function removeProjectTeam(ProjectTeam $projectTeam)
    {
        $this->projectTeams->removeElement($projectTeam);

        return $this;
    }

    /**
     * Get projectTeams.
     *
     * @return ArrayCollection|ProjectTeam[]
     */
    public function getProjectTeams()
    {
        return $this->projectTeams;
    }

    /**
     * @param int $progress
     *
     * @return $this
     */
    public function setProgress(int $progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get project progress.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("progress")
     *
     * @return array
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @return Programme|null
     */
    public function getProgramme()
    {
        return $this->programme;
    }

    /**
     * @param Programme|null $programme
     */
    public function setProgramme(Programme $programme = null)
    {
        $this->programme = $programme;
    }

    /**
     * Returns Programme id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("programme")
     *
     * @return int
     */
    public function getProgrammeId()
    {
        return $this->programme ? $this->programme->getId() : null;
    }

    /**
     * Returns Programme name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("programmeName")
     *
     * @return string
     */
    public function getProgrammeName()
    {
        return $this->programme ? $this->programme->getName() : null;
    }

    /**
     * Add projectObjective.
     *
     * @param ProjectObjective $projectObjective
     *
     * @return Project
     */
    public function addProjectObjective(ProjectObjective $projectObjective)
    {
        $this->projectObjectives[] = $projectObjective;

        return $this;
    }

    /**
     * Remove projectObjective.
     *
     * @param ProjectObjective $projectObjective
     *
     * @return Project
     */
    public function removeProjectObjective(ProjectObjective $projectObjective)
    {
        $this->projectObjectives->removeElement($projectObjective);

        return $this;
    }

    /**
     * Get projectObjectives.
     *
     * @return ArrayCollection
     */
    public function getProjectObjectives()
    {
        return $this->projectObjectives;
    }

    /**
     * Add projectLimitation.
     *
     * @param ProjectLimitation $projectLimitation
     *
     * @return Project
     */
    public function addProjectLimitation(ProjectLimitation $projectLimitation)
    {
        $this->projectLimitations[] = $projectLimitation;

        return $this;
    }

    /**
     * Remove projectLimitation.
     *
     * @param ProjectLimitation $projectLimitation
     *
     * @return Project
     */
    public function removeProjectLimitation(ProjectLimitation $projectLimitation)
    {
        $this->projectLimitations->removeElement($projectLimitation);

        return $this;
    }

    /**
     * Get projectLimitations.
     *
     * @return ArrayCollection
     */
    public function getProjectLimitations()
    {
        return $this->projectLimitations;
    }

    /**
     * Add projectDeliverable.
     *
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return Project
     */
    public function addProjectDeliverable(ProjectDeliverable $projectDeliverable)
    {
        $this->projectDeliverables[] = $projectDeliverable;

        return $this;
    }

    /**
     * Remove projectDeliverable.
     *
     * @param ProjectDeliverable $projectDeliverable
     *
     * @return Project
     */
    public function removeProjectDeliverable(ProjectDeliverable $projectDeliverable)
    {
        $this->projectDeliverables->removeElement($projectDeliverable);

        return $this;
    }

    /**
     * Get projectDeliverables.
     *
     * @return ArrayCollection
     */
    public function getProjectDeliverables()
    {
        return $this->projectDeliverables;
    }

    /**
     * Get configuration.
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set configuration.
     *
     * @param array $configuration
     *
     * @return Project
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Add projectModule.
     *
     * @param ProjectModule $projectModule
     *
     * @return Project
     */
    public function addProjectModule(ProjectModule $projectModule)
    {
        $projectModule->setProject($this);
        $this->projectModules->add($projectModule);

        return $this;
    }

    /**
     * Remove projectModule.
     *
     * @param ProjectModule $projectModule
     *
     * @return Project
     */
    public function removeProjectModule(ProjectModule $projectModule)
    {
        $this->projectDeliverables->removeElement($projectModule);

        return $this;
    }

    /**
     * Get projectModules.
     *
     * @return ArrayCollection
     */
    public function getProjectModules()
    {
        return $this->projectModules;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("projectModules")
     *
     * @return string[]
     */
    public function getProjectModulesList()
    {
        return $this
            ->projectModules
            ->map(function (ProjectModule $projectModule) {
                return $projectModule->getModule();
            })
            ->toArray()
        ;
    }

    /**
     * Add workPackageStatus.
     *
     * @param WorkPackageStatus $workPackageStatus
     *
     * @return Project
     */
    public function addWorkPackageStatus(WorkPackageStatus $workPackageStatus)
    {
        $this->workPackageStatuses[] = $workPackageStatus;

        return $this;
    }

    /**
     * Remove workPackageStatus.
     *
     * @param WorkPackageStatus $workPackageStatus
     */
    public function removeWorkPackageStatus(WorkPackageStatus $workPackageStatus)
    {
        $this->workPackageStatuses->removeElement($workPackageStatus);
    }

    /**
     * Get workPackageStatuses.
     *
     * @return ArrayCollection|WorkPackageStatus[]
     */
    public function getWorkPackageStatuses()
    {
        return $this->workPackageStatuses;
    }

    /**
     * @param Cost $cost
     *
     * @return Project
     */
    public function addCost(Cost $cost)
    {
        $this->costs[] = $cost;

        return $this;
    }

    /**
     * @param Cost $cost
     *
     * @return Project
     */
    public function removeCost(Cost $cost)
    {
        $this->costs->removeElement($cost);

        return $this;
    }

    /**
     * @return Cost[]|ArrayCollection
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * @param Unit $unit
     *
     * @return Project
     */
    public function addUnit(Unit $unit)
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * @param Unit $unit
     *
     * @return Project
     */
    public function removeUnit(Unit $unit)
    {
        $this->units->removeElement($unit);

        return $this;
    }

    /**
     * @return Unit[]|ArrayCollection
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * @param resource $resource
     *
     * @return Project
     */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
        $resource->setProject($this);

        return $this;
    }

    /**
     * @param resource $resource
     *
     * @return Project
     */
    public function removeResource(Resource $resource)
    {
        $this->resources->removeElement($resource);
        $resource->setProject(null);

        return $this;
    }

    /**
     * @return ArrayCollection|resource[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add Subteam.
     *
     * @param Subteam $subteam
     *
     * @return Project
     */
    public function addSubteam(Subteam $subteam)
    {
        $this->subteams[] = $subteam;

        return $this;
    }

    /**
     * Remove subteams.
     *
     * @param WorkPackage $workPackage
     *
     * @return Project
     */
    public function removeSubteam(Subteam $subteam)
    {
        $this->subteams->removeElement($subteam);

        return $this;
    }

    /**
     * Get subteams.
     *
     * @return ArrayCollection
     */
    public function getSubteams()
    {
        return $this->subteams;
    }

    /**
     * Add risk.
     *
     * @param Risk $risk
     *
     * @return Project
     */
    public function addRisk(Risk $risk)
    {
        $this->risks[] = $risk;

        return $this;
    }

    /**
     * Remove risk.
     *
     * @param Risk $risk
     *
     * @return Project
     */
    public function removeRisk(Risk $risk)
    {
        $this->risks->removeElement($risk);

        return $this;
    }

    /**
     * Get risks.
     *
     * @return ArrayCollection
     */
    public function getRisks()
    {
        return $this->risks;
    }

    /**
     * Add opportunity.
     *
     * @param Opportunity $opportunity
     *
     * @return Project
     */
    public function addOpportunity(Opportunity $opportunity)
    {
        $this->opportunities[] = $opportunity;

        return $this;
    }

    /**
     * Remove opportunity.
     *
     * @param Opportunity $opportunity
     *
     * @return Project
     */
    public function removeOpportunity(Opportunity $opportunity)
    {
        $this->opportunities->removeElement($opportunity);

        return $this;
    }

    /**
     * Get opportunities.
     *
     * @return ArrayCollection
     */
    public function getOpportunities()
    {
        return $this->opportunities;
    }

    /**
     * Add decision.
     *
     * @param Decision $decision
     *
     * @return Project
     */
    public function addDecision(Decision $decision)
    {
        $this->decisions[] = $decision;

        return $this;
    }

    /**
     * Remove decision.
     *
     * @param Decision $decision
     */
    public function removeDecision(Decision $decision)
    {
        $this->decisions->removeElement($decision);

        return $this;
    }

    /**
     * Get decisions.
     *
     * @return ArrayCollection|Decision[]
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

    /**
     * @return string
     */
    public function getShortNote()
    {
        return $this->shortNote;
    }

    /**
     * @param string $shortNote
     */
    public function setShortNote($shortNote)
    {
        $this->shortNote = $shortNote;
    }

    /**
     * Add opportunityStatus.
     *
     * @param OpportunityStatus $opportunityStatus
     *
     * @return Project
     */
    public function addOpportunityStatus(OpportunityStatus $opportunityStatus)
    {
        $this->opportunityStatuses[] = $opportunityStatus;

        return $this;
    }

    /**
     * Remove opportunityStatus.
     *
     * @param OpportunityStatus $opportunityStatus
     */
    public function removeOpportunityStatus(OpportunityStatus $opportunityStatus)
    {
        $this->opportunityStatuses->removeElement($opportunityStatus);

        return $this;
    }

    /**
     * Get opportunityStatuses.
     *
     * @return ArrayCollection|OpportunityStatus[]
     */
    public function getOpportunityStatuses()
    {
        return $this->opportunityStatuses;
    }

    /**
     * Add opportunityStrategy.
     *
     * @param OpportunityStrategy $oportunityStrategy
     *
     * @return Project
     */
    public function addOpportunityStrategy(OpportunityStrategy $oportunityStrategy)
    {
        $this->opportunityStrategies[] = $oportunityStrategy;

        return $this;
    }

    /**
     * Remove $oortunityStrategy.
     *
     * @param OpportunityStrategy $oprtunityStrategy
     */
    public function removeOpportunityStrategy(OpportunityStrategy $opportunityStrategy)
    {
        $this->opportunityStrategies->removeElement($opportunityStrategy);

        return $this;
    }

    /**
     * Get opportunityStrategies.
     *
     * @return ArrayCollection|OpportunityStrategy[]
     */
    public function getOpportunityStrategies()
    {
        return $this->opportunityStrategies;
    }

    /**
     * Get riskStrategies.
     *
     * @return ArrayCollection|RiskStrategy[]
     */
    public function getRiskStrategies()
    {
        return $this->riskStrategies;
    }

    /**
     * Add riskStrategy.
     *
     * @param RiskStrategy $riskStrategy
     *
     * @return Project
     */
    public function addRiskStrategy(RiskStrategy $riskStrategy)
    {
        $this->riskStrategies[] = $riskStrategy;

        return $this;
    }

    /**
     * Remove riskStrategy.
     *
     * @param RiskStrategy $riskStrategy
     */
    public function removeRiskStrategy(RiskStrategy $riskStrategy)
    {
        $this->riskStrategies->removeElement($riskStrategy);

        return $this;
    }

    /**
     * Add StatusReport.
     *
     * @param StatusReport $statusReport
     *
     * @return Project
     */
    public function addStatusReport(StatusReport $statusReport)
    {
        $this->statusReports[] = $statusReport;

        return $this;
    }

    /**
     * Remove StatusReport.
     *
     * @param StatusReport $statusReport
     */
    public function removeStatusReport(StatusReport $statusReport)
    {
        $this->statusReports->removeElement($statusReport);

        return $this;
    }

    /**
     * Get StatusReports.
     *
     * @return ArrayCollection|StatusReport[]
     */
    public function getStatusReports()
    {
        return $this->statusReports;
    }

    /**
     * Add StatusReportConfig.
     *
     * @param StatusReportConfig $statusReportConfig
     *
     * @return Project
     */
    public function addStatusReportConfig(StatusReportConfig $statusReportConfig)
    {
        $this->statusReportConfigs[] = $statusReportConfig;

        return $this;
    }

    /**
     * Remove StatusReportConfig.
     *
     * @param StatusReportConfig $statusReportConfig
     */
    public function removeStatusReportConfig(StatusReportConfig $statusReportConfig)
    {
        $this->statusReportConfigs->removeElement($statusReportConfig);

        return $this;
    }

    /**
     * Get StatusReportConfigs.
     *
     * @return ArrayCollection|StatusReportConfig[]
     */
    public function getStatusReportConfigs()
    {
        return $this->statusReportConfigs;
    }

    /**
     * Get riskStatuses.
     *
     * @return ArrayCollection|Status[]
     */
    public function getRiskStatuses()
    {
        return $this->riskStatuses;
    }

    /**
     * Add riskStatus.
     *
     * @param Status $riskStatus
     *
     * @return Project
     */
    public function addRiskStatus(Status $riskStatus)
    {
        $this->riskStatuses[] = $riskStatus;

        return $this;
    }

    /**
     * Remove riskStatus.
     *
     * @param Status $riskStatus
     */
    public function removeRiskStatus(Status $riskStatus)
    {
        $this->riskStatuses->removeElement($riskStatus);

        return $this;
    }

    /**
     * Returns if a project is new.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("isNew")
     *
     * @return string
     */
    public function getIsNew()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);
        $createdAt = clone $this->getCreatedAt();
        $createdAt->setTime(0, 0, 0);

        return intval($today->diff($createdAt)->format('%a')) <= 7;
    }

    /**
     * Get projectDepartments.
     *
     * @return ArrayCollection|ProjectDepartment[]
     */
    public function getProjectDepartments()
    {
        return $this->projectDepartments;
    }

    /**
     * Add projectDepartment.
     *
     * @param ProjectDepartment $projectDepartment
     *
     * @return Project
     */
    public function addProjectDepartment(ProjectDepartment $projectDepartment)
    {
        $this->projectDepartments[] = $projectDepartment;

        return $this;
    }

    /**
     * Remove projectDepartment.
     *
     * @param ProjectDepartment $projectDepartment
     */
    public function removeProjectDepartment(ProjectDepartment $projectDepartment)
    {
        $this->projectDepartments->removeElement($projectDepartment);

        return $this;
    }

    /**
     * @param ColorStatus|null $colorStatus
     *
     * @return Project
     */
    public function setColorStatus(ColorStatus $colorStatus = null)
    {
        $this->colorStatus = $colorStatus;

        return $this;
    }

    /**
     * @return ColorStatus|null
     */
    public function getColorStatus()
    {
        return $this->colorStatus;
    }

    /**
     * @return null|int
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatus")
     */
    public function getColorStatusId()
    {
        return $this->colorStatus
            ? $this->colorStatus->getId()
            : null
        ;
    }

    /**
     * @return null|string
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatusName")
     */
    public function getColorStatusName()
    {
        return $this->colorStatus
            ? $this->colorStatus->getName()
            : null
        ;
    }

    /**
     * @return null|string
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatusColor")
     */
    public function getColorStatusColor()
    {
        return $this->colorStatus
            ? $this->colorStatus->getColor()
            : null
        ;
    }

    /**
     * Returns the project overall status.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("overallStatus")
     *
     * @return string
     */
    public function getOverallStatus()
    {
        $status = self::STATUS_GREEN;
        foreach ($this->workPackages as $wp) {
            $colorStatus = $wp->getColorStatus();
            if ($colorStatus) {
                if (ColorStatus::STATUS_IN_PROGRESS === $colorStatus->getName()) {
                    $status = self::STATUS_YELLOW;
                } elseif (ColorStatus::STATUS_NOT_STARTED === $colorStatus->getName()) {
                    $status = self::STATUS_RED;
                    break;
                }
            }
        }

        return $status;
    }

    /**
     * Get projectCloseDowns.
     *
     * @return ArrayCollection|ProjectCloseDown[]
     */
    public function getProjectCloseDowns()
    {
        return $this->projectCloseDowns;
    }

    /**
     * Add projectCloseDown.
     *
     * @param ProjectCloseDown $projectCloseDown
     *
     * @return Project
     */
    public function addProjectCloseDown(ProjectCloseDown $projectCloseDown)
    {
        $this->projectCloseDowns[] = $projectCloseDown;

        return $this;
    }

    /**
     * Remove projectCloseDown.
     *
     * @param ProjectCloseDown $projectCloseDown
     */
    public function removeProjectCloseDown(ProjectCloseDown $projectCloseDown)
    {
        $this->projectCloseDowns->removeElement($projectCloseDown);

        return $this;
    }

    /**
     * Add projectRole.
     *
     * @param ProjectRole $projectRole
     *
     * @return Project
     */
    public function addProjectRole(ProjectRole $projectRole)
    {
        $this->projectRoles[] = $projectRole;

        return $this;
    }

    /**
     * Remove projectRole.
     *
     * @param ProjectRole $projectRole
     *
     * @return Project;
     */
    public function removeProjectRole(ProjectRole $projectRole)
    {
        $this->projectRoles->removeElement($projectRole);

        return $this;
    }

    /**
     * Remove projectRole.
     *
     * @param ProjectRole[] $projectRole
     * @param mixed         $projectRoles
     *
     * @return Project;
     */
    public function setProjectRoles($projectRoles)
    {
        $this->projectRoles = $projectRoles;

        return $this;
    }

    /**
     * Get projectRoles.
     *
     * @return ArrayCollection|ProjectRole[]
     */
    public function getProjectRoles()
    {
        return $this->projectRoles;
    }

    /**
     * @param string $propertyMethod
     * @param int    $mode
     *
     * @return null|\DateTime
     */
    private function getDatesFromTasks($propertyMethod, $mode = 1)
    {
        $out = $this
            ->workPackages
            ->filter(function (WorkPackage $workPackage) {
                return WorkPackage::TYPE_TASK === $workPackage->getType();
            })
            ->map(function (WorkPackage $workPackage) use ($propertyMethod) {
                return $workPackage->{$propertyMethod}();
            })
        ;

        $out = array_reduce(
            $out->toArray(),
            function ($item, $carry) use ($mode) {
                if ($item instanceof \DateTime && $carry instanceof \DateTime) {
                    if (1 === $mode) {
                        return $carry > $item ? $carry : $item;
                    } else {
                        return $carry < $item ? $carry : $item;
                    }
                }

                if (!$item && $carry) {
                    return $carry;
                } elseif ($item && !$carry) {
                    return $item;
                } else {
                    return null;
                }
            },
            null
        );

        if (!$out && false !== stripos($propertyMethod, 'start')) {
            $out = $this->createdAt;
        }

        return $out;
    }

    private function getDatesFromTasksDiff($startPropertyMethod, $endPropertyMethod)
    {
        $start = $this->getDatesFromTasks($startPropertyMethod, -1);
        $end = $this->getDatesFromTasks($endPropertyMethod, 1);

        if ($start && $end) {
            return $end->diff($start)->days;
        } elseif (!$start && $end) {
            return $this->createdAt->diff($end)->days;
        } else {
            return 1;
        }
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("scheduledStartAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getScheduledStartDate()
    {
        return $this->getDatesFromTasks('getScheduledStartAt', -1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("scheduledFinishAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getScheduledFinishDate()
    {
        return $this->getDatesFromTasks('getScheduledFinishAt', 1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("scheduledDuration")
     */
    public function getScheduledDuration()
    {
        return $this->getDatesFromTasksDiff('getScheduledStartAt', 'getScheduledFinishAt');
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("forecastStartAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getForecastStartDate()
    {
        return $this->getDatesFromTasks('getForecastStartAt', -1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("forecastFinishAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getForecastFinishDate()
    {
        return $this->getDatesFromTasks('getForecastFinishAt', 1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("forecastDuration")
     */
    public function getForecastDuration()
    {
        return $this->getDatesFromTasksDiff('getForecastStartAt', 'getForecastFinishAt');
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("actualStartAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getActualStartDate()
    {
        return $this->getDatesFromTasks('getActualStartAt', -1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("actualFinishAt")
     * @Serializer\Type("DateTime<'Y-m-d'>")
     */
    public function getActualFinishDate()
    {
        return $this->getDatesFromTasks('getActualFinishAt', 1);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("actualDuration")
     */
    public function getActualDuration()
    {
        return $this->getDatesFromTasksDiff('getActualStartAt', 'getActualFinishAt');
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    public function hasProjectModule(string $module)
    {
        return in_array($module, $this->getProjectModulesList(), true);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("distributionLists")
     */
    public function getDistributionListsSimple()
    {
        return $this
            ->distributionLists
            ->map(function (DistributionList $dl) {
                return [
                    'id' => $dl->getId(),
                    'name' => $dl->getName(),
                    'sequence' => $dl->getSequence(),
                    'users' => $dl
                        ->getUsers()
                        ->map(function (User $user) {
                            return [
                                'id' => $user->getId(),
                            ];
                        }),
                ];
            })
        ;
    }
}
