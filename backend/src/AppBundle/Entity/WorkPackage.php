<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * WorkPackage.
 *
 * @ORM\Table(name="work_package", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="puid_project_unique", columns={"puid", "project_id"}),
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkPackageRepository")
 */
class WorkPackage
{
    const TYPE_PHASE = 0;
    const TYPE_MILESTONE = 1;
    const TYPE_TASK = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Project Unique ID - An ID that is unique within the project workspace.
     *
     * @var string
     *
     * @ORM\Column(name="puid", type="string", length=128)
     */
    private $puid;

    /**
     * @var int
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="external_id", type="integer", unique=true, nullable=true)
     */
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, options={"default": "WorkPackage"})
     */
    private $name = 'WorkPackage';

    /**
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="phase_id", referencedColumnName="id")
     * })
     */
    private $phase;

    /**
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="milestone_id", referencedColumnName="id")
     * })
     */
    private $milestone;

    /**
     * @var WorkPackage
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackage", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;

    /**
     * @var ColorStatus|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ColorStatus")
     * @ORM\JoinColumn(name="color_status_id")
     */
    private $colorStatus;

    /**
     * @var int
     * @ORM\Column(name="progress", type="integer", options={"default"=0})
     */
    private $progress = 0;

    /**
     * @var Project|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Project", inversedBy="workPackages")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var User|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="responsibility_id")
     */
    private $responsibility;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="scheduled_start_at", type="date", nullable=true)
     */
    private $scheduledStartAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="scheduled_finish_at", type="date", nullable=true)
     */
    private $scheduledFinishAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="forecast_start_at", type="date", nullable=true)
     */
    private $forecastStartAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="forecast_finish_at", type="date", nullable=true)
     */
    private $forecastFinishAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="actual_start_at", type="date", nullable=true)
     */
    private $actualStartAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'Y-m-d'>")
     *
     * @ORM\Column(name="actual_finish_at", type="date", nullable=true)
     */
    private $actualFinishAt;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="results", type="text", nullable=true)
     */
    private $results;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_key_milestone", type="boolean", nullable=false, options={"default"=0})
     */
    private $isKeyMilestone = false;

    /**
     * @var ArrayCollection|Assignment[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Assignment", mappedBy="workPackage")
     */
    private $assignments;

    /**
     * @var Calendar
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Calendar", inversedBy="workPackages")
     * @ORM\JoinColumn(name="calendar_id", referencedColumnName="id")
     */
    private $calendar;

    /**
     * @var ArrayCollection|Label[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Label", inversedBy="workPackages")
     * @ORM\JoinTable(
     *     name="work_package_label",
     *     joinColumns={
     *         @ORM\JoinColumn(name="work_package_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="label_id")
     *     }
     * )
     */
    private $labels;

    /**
     * @var WorkPackageStatus|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackageStatus", inversedBy="workPackages")
     * @ORM\JoinColumn(name="work_package_status_id")
     */
    private $workPackageStatus;

    /**
     * @var WorkPackageCategory|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\WorkPackageCategory", inversedBy="workPackages")
     * @ORM\JoinColumn(name="work_package_category_id")
     */
    private $workPackageCategory;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\WorkPackage", inversedBy="dependants")
     * @ORM\JoinTable(
     *     name="work_package_dependency",
     *     joinColumns={
     *         @ORM\JoinColumn(name="dependant_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="dependency_id")
     *     }
     * )
     */
    private $dependencies;

    /**
     * @var ArrayCollection|WorkPackage[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\WorkPackage", mappedBy="dependencies", cascade={"all"})
     */
    private $dependants;

    /**
     * @var Media[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Media", inversedBy="workPackages", cascade={"all"})
     * @ORM\JoinTable(
     *     name="work_package_media",
     *     joinColumns={
     *         @ORM\JoinColumn(name="work_package_id", onDelete="CASCADE")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="media_id", onDelete="CASCADE")
     *     }
     * )
     */
    private $medias;

    /**
     * @var bool
     *
     * @ORM\Column(name="automatic_schedule", type="boolean", options={"default"=false})
     */
    private $automaticSchedule = false;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=64, nullable=true)
     */
    private $duration;

    /**
     * @var Cost[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cost", mappedBy="workPackage", cascade={"persist"})
     */
    private $costs;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Exclude()
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * WorkPackage constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->assignments = new ArrayCollection();
        $this->labels = new ArrayCollection();
        $this->dependencies = new ArrayCollection();
        $this->dependants = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->costs = new ArrayCollection();
        $this->puid = microtime(true) * 1000000;
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
     * Set puid.
     *
     * @param string $puid
     *
     * @return WorkPackage
     */
    public function setPuid($puid)
    {
        $this->puid = $puid;

        return $this;
    }

    /**
     * Get puid.
     *
     * @return string
     */
    public function getPuid()
    {
        return $this->puid;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return WorkPackage
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
     * Set progress.
     *
     * @param int $progress
     *
     * @return WorkPackage
     */
    public function setProgress($progress)
    {
        $this->progress = (int) $progress;

        return $this;
    }

    /**
     * Get progress.
     *
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set scheduledStartAt.
     *
     * @param \DateTime $scheduledStartAt
     *
     * @return WorkPackage
     */
    public function setScheduledStartAt(\DateTime $scheduledStartAt = null)
    {
        $this->scheduledStartAt = $scheduledStartAt;

        return $this;
    }

    /**
     * Get scheduledStartAt.
     *
     * @return \DateTime
     */
    public function getScheduledStartAt()
    {
        return $this->scheduledStartAt;
    }

    /**
     * Set scheduledFinishAt.
     *
     * @param \DateTime $scheduledFinishAt
     *
     * @return WorkPackage
     */
    public function setScheduledFinishAt(\DateTime $scheduledFinishAt = null)
    {
        $this->scheduledFinishAt = $scheduledFinishAt;

        return $this;
    }

    /**
     * Get scheduledFinishAt.
     *
     * @return \DateTime
     */
    public function getScheduledFinishAt()
    {
        return $this->scheduledFinishAt;
    }

    /**
     * Set forecastStartAt.
     *
     * @param \DateTime $forecastStartAt
     *
     * @return WorkPackage
     */
    public function setForecastStartAt(\DateTime $forecastStartAt = null)
    {
        $this->forecastStartAt = $forecastStartAt;

        return $this;
    }

    /**
     * Get forecastStartAt.
     *
     * @return \DateTime
     */
    public function getForecastStartAt()
    {
        return $this->forecastStartAt;
    }

    /**
     * Set forecastFinishAt.
     *
     * @param \DateTime $forecastFinishAt
     *
     * @return WorkPackage
     */
    public function setForecastFinishAt(\DateTime $forecastFinishAt = null)
    {
        $this->forecastFinishAt = $forecastFinishAt;

        return $this;
    }

    /**
     * Get forecastFinishAt.
     *
     * @return \DateTime
     */
    public function getForecastFinishAt()
    {
        return $this->forecastFinishAt;
    }

    /**
     * Set actualStartAt.
     *
     * @param \DateTime $actualStartAt
     *
     * @return WorkPackage
     */
    public function setActualStartAt(\DateTime $actualStartAt = null)
    {
        $this->actualStartAt = $actualStartAt;

        return $this;
    }

    /**
     * Get actualStartAt.
     *
     * @return \DateTime
     */
    public function getActualStartAt()
    {
        return $this->actualStartAt;
    }

    /**
     * Set actualFinishAt.
     *
     * @param \DateTime $actualFinishAt
     *
     * @return WorkPackage
     */
    public function setActualFinishAt(\DateTime $actualFinishAt = null)
    {
        $this->actualFinishAt = $actualFinishAt;

        return $this;
    }

    /**
     * Get actualFinishAt.
     *
     * @return \DateTime
     */
    public function getActualFinishAt()
    {
        return $this->actualFinishAt;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return WorkPackage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set results.
     *
     * @param string $results
     *
     * @return WorkPackage
     */
    public function setResults($results)
    {
        $this->results = $results;

        return $this;
    }

    /**
     * Get results.
     *
     * @return string
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Set isKeyMilestone.
     *
     * @param bool $isKeyMilestone
     *
     * @return WorkPackage
     */
    public function setIsKeyMilestone($isKeyMilestone)
    {
        $this->isKeyMilestone = $isKeyMilestone;

        return $this;
    }

    /**
     * Get isKeyMilestone.
     *
     * @return bool
     */
    public function getIsKeyMilestone()
    {
        return $this->isKeyMilestone;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return WorkPackage
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
     * @return WorkPackage
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
     * @param WorkPackage|null $workPackage
     *
     * @return WorkPackage
     */
    public function setPhase(WorkPackage $workPackage = null)
    {
        $this->phase = $workPackage;
        foreach ($this->getChildren() as $child) {
            $child->setPhase($workPackage);
        }

        return $this;
    }

    /**
     * @return WorkPackage|null
     */
    public function getPhase()
    {
        return $this->phase;
    }

    /**
     * @return int|null
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("phase")
     */
    public function getPhaseId()
    {
        return $this->phase ? $this->phase->getId() : null;
    }

    /**
     * @return string|null
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("phaseName")
     */
    public function getPhaseName()
    {
        return $this->phase ? (string) $this->phase : null;
    }

    /**
     * @param WorkPackage|null $workPackage
     *
     * @return WorkPackage
     */
    public function setMilestone(WorkPackage $workPackage = null)
    {
        $this->milestone = $workPackage;
        foreach ($this->getChildren() as $child) {
            $child->setMilestone($workPackage);
        }

        return $this;
    }

    /**
     * @return WorkPackage
     */
    public function getMilestone()
    {
        return $this->milestone;
    }

    /**
     * @return int|null
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("milestone")
     */
    public function getMilestoneId()
    {
        return $this->milestone ? $this->milestone->getId() : null;
    }

    /**
     * @return string|null
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("milestoneName")
     */
    public function getMilestoneName()
    {
        return $this->milestone ? (string) $this->milestone : null;
    }

    /**
     * Set parent.
     *
     * @param WorkPackage $parent
     *
     * @return WorkPackage
     */
    public function setParent(WorkPackage $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return WorkPackage
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set colorStatus.
     *
     * @param ColorStatus $colorStatus
     *
     * @return WorkPackage
     */
    public function setColorStatus(ColorStatus $colorStatus = null)
    {
        $this->colorStatus = $colorStatus;

        return $this;
    }

    /**
     * Get colorStatus.
     *
     * @return ColorStatus
     */
    public function getColorStatus()
    {
        return $this->colorStatus;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return WorkPackage
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
     * Returns responsibility avatar.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibilityAvatar")
     *
     * @return string
     */
    public function getResponsibilityAvatar()
    {
        if ($this->responsibility) {
            return $this->responsibility->getAvatar() ?
                $this->responsibility->getAvatar() :
                $this->responsibility->getGravatar()
            ;
        }

        return null;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return WorkPackage
     */
    public function setProject(Project $project = null)
    {
        $this->project = $project;
        foreach ($this->getChildren() as $child) {
            $child->setProject($project);
        }

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
     * Add assignment.
     *
     * @param Assignment $assignment
     *
     * @return WorkPackage
     */
    public function addAssignment(Assignment $assignment)
    {
        $assignment->setWorkPackage($this);
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment.
     *
     * @param Assignment $assignment
     *
     * @return WorkPackage
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

    /**
     * Set calendar.
     *
     * @param Calendar $calendar
     *
     * @return WorkPackage
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
     * Returns parent id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("parent")
     *
     * @return string
     */
    public function getParentId()
    {
        return $this->parent ? $this->parent->getId() : null;
    }

    /**
     * Returns parent name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("parentName")
     *
     * @return string
     */
    public function getParentName()
    {
        return $this->parent ? $this->parent->getName() : null;
    }

    /**
     * Returns ColorStatus id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatus")
     *
     * @return string
     */
    public function getColorStatusId()
    {
        return $this->colorStatus ? $this->colorStatus->getId() : null;
    }

    /**
     * Returns ColorStatus name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatusName")
     *
     * @return string
     */
    public function getColorStatusName()
    {
        return $this->colorStatus ? $this->colorStatus->getName() : null;
    }

    /**
     * Returns ColorStatus color.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("colorStatusColor")
     *
     * @return string
     */
    public function getColorStatusColor()
    {
        return $this->colorStatus ? $this->colorStatus->getColor() : null;
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
     * Returns Calendar id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("calendar")
     *
     * @return int
     */
    public function getCalendarId()
    {
        return $this->calendar ? $this->calendar->getId() : null;
    }

    /**
     * Returns Calendar name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("calendarName")
     *
     * @return string
     */
    public function getCalendarName()
    {
        return $this->calendar ? $this->calendar->getName() : null;
    }

    /**
     * Add label.
     *
     * @param Label $label
     *
     * @return WorkPackage
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
     *
     * @return WorkPackage
     */
    public function removeLabel(Label $label)
    {
        $this->labels->removeElement($label);

        return $this;
    }

    /**
     * Get labels.
     *
     * @return ArrayCollection
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return null|int
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("label")
     */
    public function getLabelId()
    {
        return $this->labels && $this->labels->count()
            ? $this->labels->first()->getId()
            : null
        ;
    }

    /**
     * @return string
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("labelName")
     */
    public function getLabelName()
    {
        return $this->labels && $this->labels->count()
            ? (string) $this->labels->first()
            : null
        ;
    }

    /**
     * @return WorkPackageStatus|null
     */
    public function getWorkPackageStatus()
    {
        return $this->workPackageStatus;
    }

    /**
     * @param WorkPackageStatus|null $workPackageStatus
     */
    public function setWorkPackageStatus(WorkPackageStatus $workPackageStatus)
    {
        $this->workPackageStatus = $workPackageStatus;
    }

    /**
     * Returns WorkPackageStatus id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageStatus")
     *
     * @return string
     */
    public function getWorkPackageStatusId()
    {
        return $this->workPackageStatus ? $this->workPackageStatus->getId() : null;
    }

    /**
     * Returns WorkPackageStatus name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageStatusName")
     *
     * @return string
     */
    public function getWorkPackageStatusName()
    {
        return $this->workPackageStatus ? $this->workPackageStatus->getName() : null;
    }

    /**
     * Set externalId.
     *
     * @param int|null $externalId
     *
     * @return WorkPackage
     */
    public function setExternalId($externalId = null)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId.
     *
     * @return int|null
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @return WorkPackageCategory|null
     */
    public function getWorkPackageCategory()
    {
        return $this->workPackageCategory;
    }

    /**
     * @param WorkPackageCategory|null $workPackageCategory
     */
    public function setWorkPackageCategory(WorkPackageCategory $workPackageCategory = null)
    {
        $this->workPackageCategory = $workPackageCategory;
    }

    /**
     * Returns WorkPackageCategory id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageCategory")
     *
     * @return string
     */
    public function getWorkPackageCategoryId()
    {
        return $this->workPackageCategory ? $this->workPackageCategory->getId() : null;
    }

    /**
     * Returns WorkPackageCategory name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("workPackageCategoryName")
     *
     * @return string
     */
    public function getWorkPackageCategoryName()
    {
        return $this->workPackageCategory ? $this->workPackageCategory->getName() : null;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Add dependency.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackage
     */
    public function addDependency(WorkPackage $workPackage)
    {
        $this->dependencies[] = $workPackage;

        return $this;
    }

    /**
     * Remove dependency.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackage
     */
    public function removeDependency(WorkPackage $workPackage)
    {
        $this->dependencies->removeElement($workPackage);

        return $this;
    }

    /**
     * Get dependencies.
     *
     * @return ArrayCollection
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Add dependant.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackage
     */
    public function addDependant(WorkPackage $workPackage)
    {
        $this->dependants[] = $workPackage;
        $workPackage->addDependency($this);

        return $this;
    }

    /**
     * Remove dependant.
     *
     * @param WorkPackage $workPackage
     *
     * @return WorkPackage
     */
    public function removeDependant(WorkPackage $workPackage)
    {
        $this->dependants->removeElement($workPackage);
        $workPackage->removeDependency($this);

        return $this;
    }

    /**
     * Get dependants.
     *
     * @return ArrayCollection
     */
    public function getDependants()
    {
        return $this->dependants;
    }

    /**
     * Add child.
     *
     * @param WorkPackage $child
     *
     * @return WorkPackage
     */
    public function addChild(WorkPackage $child)
    {
        $child->setPhase($this->getPhase());
        $child->setMilestone($this->getMilestone());
        $child->setParent($this);
        $child->setProject($this->getProject());
        $this->children[] = $child;
        $child->setType(static::TYPE_TASK);

        return $this;
    }

    /**
     * Remove child.
     *
     * @param WorkPackage $child
     */
    public function removeChild(WorkPackage $child)
    {
        $this->children->removeElement($child);
        $child->setParent(null);
        $child->setMilestone(null);
        $child->setPhase(null);
    }

    /**
     * Get children.
     *
     * @return ArrayCollection|WorkPackage[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param Media $media
     *
     * @return WorkPackage
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * @param Media $media
     *
     * @return WorkPackage
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);

        return $this;
    }

    /**
     * @return Media[]|ArrayCollection
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * @return bool
     */
    public function isAutomaticSchedule(): bool
    {
        return $this->automaticSchedule;
    }

    /**
     * @param bool $automaticSchedule
     */
    public function setAutomaticSchedule(bool $automaticSchedule)
    {
        $this->automaticSchedule = $automaticSchedule;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @param Cost $cost
     *
     * @return WorkPackage
     */
    public function addCost(Cost $cost)
    {
        $cost->setWorkPackage($this);
        $cost->setProject($this->getProject());
        $this->costs[] = $cost;

        return $this;
    }

    /**
     * @param Cost $cost
     *
     * @return WorkPackage
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
}
