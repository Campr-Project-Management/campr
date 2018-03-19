<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Contract.
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 * @UniqueEntity(
 *     fields="name",
 *     errorPath="name",
 *     message="unique.name"
 *  )
 */
class Contract
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="project_start_event", type="text", nullable=true)
     */
    private $projectStartEvent;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="contracts")
     * @ORM\JoinColumn(name="user_id", nullable=false)
     */
    private $createdBy;

    /**
     * @var Project
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="contracts")
     * @ORM\JoinColumn(name="project_id", nullable=false)
     */
    private $project;

    /**
     * @var ArrayCollection|ProjectObjective[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectObjective", mappedBy="contract")
     */
    private $projectObjectives;

    /**
     * @var ArrayCollection|ProjectLimitation[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectLimitation", mappedBy="contract")
     */
    private $projectLimitations;

    /**
     * @var ArrayCollection|ProjectDeliverable[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjectDeliverable", mappedBy="contract")
     */
    private $projectDeliverables;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="proposed_start_date", type="date", nullable=true)
     */
    private $proposedStartDate;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="proposed_end_date", type="date", nullable=true)
     */
    private $proposedEndDate;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="forecast_start_date", type="date", nullable=true)
     */
    private $forecastStartDate;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="forecast_end_date", type="date", nullable=true)
     */
    private $forecastEndDate;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="frozen", type="boolean", nullable=false, options={"default"=0})
     */
    private $frozen = false;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="approved_at", type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * Contract constructor.
     */
    public function __construct()
    {
        $this->projectObjectives = new ArrayCollection();
        $this->projectLimitations = new ArrayCollection();
        $this->projectDeliverables = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Contract
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
     * Set description.
     *
     * @param string $description
     *
     * @return Contract
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
     * @return string
     */
    public function getProjectStartEvent()
    {
        return $this->projectStartEvent;
    }

    /**
     * @param string $projectStartEvent
     */
    public function setProjectStartEvent($projectStartEvent)
    {
        $this->projectStartEvent = $projectStartEvent;

        return $this;
    }

    /**
     * Set proposedStartDate.
     *
     * @param \DateTime $proposedStartDate
     *
     * @return Contract
     */
    public function setProposedStartDate(\DateTime $proposedStartDate = null)
    {
        $this->proposedStartDate = $proposedStartDate;

        return $this;
    }

    /**
     * Get proposedStartDate.
     *
     * @return \DateTime
     */
    public function getProposedStartDate()
    {
        return $this->proposedStartDate;
    }

    /**
     * Set proposedEndDate.
     *
     * @param \DateTime $proposedEndDate
     *
     * @return Contract
     */
    public function setProposedEndDate(\DateTime $proposedEndDate = null)
    {
        $this->proposedEndDate = $proposedEndDate;

        return $this;
    }

    /**
     * Get proposedEndDate.
     *
     * @return \DateTime
     */
    public function getProposedEndDate()
    {
        return $this->proposedEndDate;
    }

    /**
     * Set forecastStartDate.
     *
     * @param \DateTime $forecastStartDate
     *
     * @return Contract
     */
    public function setForecastStartDate(\DateTime $forecastStartDate = null)
    {
        $this->forecastStartDate = $forecastStartDate;

        return $this;
    }

    /**
     * Get forecastStartDate.
     *
     * @return \DateTime
     */
    public function getForecastStartDate()
    {
        return $this->forecastStartDate;
    }

    /**
     * Set forecastEndDate.
     *
     * @param \DateTime $forecastEndDate
     *
     * @return Contract
     */
    public function setForecastEndDate(\DateTime $forecastEndDate = null)
    {
        $this->forecastEndDate = $forecastEndDate;

        return $this;
    }

    /**
     * Get forecastEndDate.
     *
     * @return \DateTime
     */
    public function getForecastEndDate()
    {
        return $this->forecastEndDate;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Contract
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
     * @return Contract
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
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return Contract
     */
    public function setCreatedBy(User $createdBy)
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
     * Set project.
     *
     * @param Project $project
     *
     * @return Contract
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
     * Returns createdBy fullname.
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
     * Add projectObjective.
     *
     * @param ProjectObjective $projectObjective
     *
     * @return Contract
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
     * @return Contract
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
     * @return Contract
     */
    public function addProjectLimitation(ProjectLimitation $projectLimitation)
    {
        $this->projectLimitations[] = $projectLimitation;

        return $this;
    }

    /**
     * Remove projectLimitation.
     *
     * @param ProjectObjective $projectObjective
     *
     * @return Contract
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
     * @return Contract
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
     * @return Contract
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
     * @return bool
     */
    public function isFrozen()
    {
        return $this->frozen;
    }

    /**
     * @param bool $frozen
     *
     * @return self
     */
    public function setFrozen($frozen)
    {
        $this->frozen = $frozen;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getApprovedAt()
    {
        return $this->approvedAt;
    }

    /**
     * @param \DateTime|null $approvedAt
     *
     * @return self
     */
    public function setApprovedAt(\DateTime $approvedAt = null)
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }
}
