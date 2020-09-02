<?php

namespace AppBundle\Entity;

use Component\Date\DateRange;
use Component\Media\MediasAwareInterface;
use Component\Resource\Cloner\CloneableInterface;
use Component\Resource\Model\ResourceInterface;
use Component\Resource\Model\TimestampableInterface;
use Component\Resource\Model\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * Meeting.
 *
 * @ORM\Table(name="meeting")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingRepository")
 */
class Meeting implements MediasAwareInterface, ResourceInterface, CloneableInterface, TimestampableInterface
{
    use TimestampableTrait;

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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="meetings")
     * @Assert\NotBlank(message="not_blank.project")
     * @ORM\JoinColumn(name="project_id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var MeetingCategory|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MeetingCategory")
     * @ORM\JoinColumn(name="meeting_category_id", onDelete="CASCADE")
     * @Assert\NotBlank(message="not_blank.meeting_category")
     */
    private $meetingCategory;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'H:i'>")
     *
     * @ORM\Column(name="start", type="time")
     * @Assert\NotBlank(message="not_blank.start")
     * @Assert\Time()
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'H:i'>")
     *
     * @ORM\Column(name="end", type="time")
     * @Assert\NotBlank(message="not_blank.end")
     * @Assert\Time()
     * @AppAssert\GreaterThan(propertyPath="start", message="invalid.end_time_greater_than_start_time")
     */
    private $end;

    /**
     * @var ArrayCollection|MeetingObjective[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingObjective", mappedBy="meeting", cascade={"all"})
     */
    private $meetingObjectives;

    /**
     * @var ArrayCollection|MeetingParticipant[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingParticipant", mappedBy="meeting", cascade={"all"})
     */
    private $meetingParticipants;

    /**
     * @var ArrayCollection|MeetingReport[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingReport", mappedBy="meeting", cascade={"all"})
     */
    private $meetingReports;

    /**
     * @var ArrayCollection|MeetingAgenda[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingAgenda", mappedBy="meeting", cascade={"all"})
     */
    private $meetingAgendas;

    /**
     * @var ArrayCollection|Media[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Media", mappedBy="meetings", cascade={"all"})
     */
    private $medias;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Decision", mappedBy="meeting", cascade={"all"})
     * @Assert\Valid()
     */
    private $decisions;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="meeting", cascade={"all"})
     * @Assert\Valid()
     */
    private $todos;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @Serializer\Exclude()
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Info", mappedBy="meeting", cascade={"all"})
     * @Assert\Valid()
     */
    private $infos;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="meetings", cascade={"all"})
     * @Assert\Count(min=1, minMessage="not_blank.distribution_list")
     */
    private $distributionLists;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ownedMeetings")
     * @ORM\JoinColumn(name="user_id", onDelete="SET NULL")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var String
     */
    public $jitsiLink;

    /**
     * Meeting constructor.
     */
    public function __construct()
    {
        $this->meetingParticipants = new ArrayCollection();
        $this->meetingReports = new ArrayCollection();
        $this->meetingObjectives = new ArrayCollection();
        $this->meetingAgendas = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->infos = new ArrayCollection();
        $this->distributionLists = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
     * @return Meeting
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
     * Set location.
     *
     * @param string $location
     *
     * @return Meeting
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Meeting
     */
    public function setDate(\DateTime $date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set start.
     *
     * @param \DateTime $start
     *
     * @return Meeting
     */
    public function setStart(\DateTime $start = null)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start.
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end.
     *
     * @param \DateTime $end
     *
     * @return Meeting
     */
    public function setEnd(\DateTime $end = null)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end.
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Set project.
     *
     * @param Project $project
     *
     * @return Meeting
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
    public function getPojectId()
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
     * Add meetingParticipant.
     *
     * @param MeetingParticipant $meetingParticipant
     *
     * @return Meeting
     */
    public function addMeetingParticipant(MeetingParticipant $meetingParticipant)
    {
        $this->meetingParticipants[] = $meetingParticipant;
        $meetingParticipant->setMeeting($this);
    }

    /**
     * Remove meetingParticipant.
     *
     * @param MeetingParticipant $meetingParticipant
     */
    public function removeMeetingParticipant(MeetingParticipant $meetingParticipant)
    {
        $this->meetingParticipants->removeElement($meetingParticipant);
        $meetingParticipant->setMeeting(null);
    }

    /**
     * Get meetingParticipants.
     *
     * @return ArrayCollection|Collection
     */
    public function getMeetingParticipants()
    {
        return $this->meetingParticipants;
    }

    /**
     * Add meetingAgenda.
     *
     * @param MeetingAgenda $meetingAgenda
     *
     * @return Meeting
     */
    public function addMeetingAgenda(MeetingAgenda $meetingAgenda)
    {
        $this->meetingAgendas[] = $meetingAgenda;
        $meetingAgenda->setMeeting($this);
    }

    /**
     * Remove meetingAgenda.
     *
     * @param MeetingAgenda $meetingAgenda
     */
    public function removeMeetingAgenda(MeetingAgenda $meetingAgenda)
    {
        $this->meetingAgendas->removeElement($meetingAgenda);
    }

    /**
     * Get meetingAgendas.
     *
     * @return Collection
     */
    public function getMeetingAgendas()
    {
        return $this->meetingAgendas;
    }

    /**
     * Add media.
     *
     * @param Media $media
     *
     * @return Meeting
     */
    public function addMedia(Media $media)
    {
        $media->addMeeting($this);
        $this->medias->add($media);

        return $this;
    }

    /**
     * @param Media[]|ArrayCollection $medias
     */
    public function setMedias($medias)
    {
        foreach ($medias as $media) {
            $media->addMeeting($this);
        }

        $this->medias = $medias;
    }

    /**
     * @param Media $media
     *
     * @return $this
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
        $media->removeMeeting($this);
    }

    /**
     * Get medias.
     *
     * @return ArrayCollection|Media[]
     */
    public function getMedias()
    {
        return $this->medias;
    }

    /**
     * Add decision.
     *
     * @param Decision $decision
     *
     * @return Meeting
     */
    public function addDecision(Decision $decision)
    {
        $this->decisions[] = $decision;
        $decision->setMeeting($this);
    }

    /**
     * @param Decision $decision
     *
     * @return $this
     */
    public function removeDecision(Decision $decision)
    {
        $this->decisions->removeElement($decision);
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
     * Add todo.
     *
     * @param Todo $todo
     *
     * @return Meeting
     */
    public function addTodo(Todo $todo)
    {
        $this->todos[] = $todo;
        $todo->setMeeting($this);
    }

    /**
     * Remove todo.
     *
     * @param Todo $todo
     */
    public function removeTodo(Todo $todo)
    {
        $this->todos->removeElement($todo);
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
     * Add Info.
     *
     * @param Info $info
     *
     * @return Meeting
     */
    public function addInfo(Info $info)
    {
        $this->infos[] = $info;
        $info->setMeeting($this);
    }

    /**
     * Remove Info.
     *
     * @param Info $info
     *
     * @return Meeting
     */
    public function removeInfo(Info $info)
    {
        $this->infos->removeElement($info);
    }

    /**
     * Get Infos.
     *
     * @return ArrayCollection|Info[]
     */
    public function getInfos()
    {
        return $this->infos;
    }

    /**
     * Add distributionList.
     *
     * @param DistributionList $distributionList
     *
     * @return Meeting
     */
    public function addDistributionList(DistributionList $distributionList)
    {
        $distributionList->addMeeting($this);
        $this->distributionLists->add($distributionList);
    }

    /**
     * @param DistributionList $distributionList
     *
     * @return $this
     */
    public function removeDistributionList(DistributionList $distributionList)
    {
        $this->distributionLists->removeElement($distributionList);
        $distributionList->removeMeeting($this);
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
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return Meeting
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
     * @return MeetingCategory|null
     */
    public function getMeetingCategory()
    {
        return $this->meetingCategory;
    }

    /**
     * @param MeetingCategory|null $meetingCategory
     */
    public function setMeetingCategory(MeetingCategory $meetingCategory = null)
    {
        $this->meetingCategory = $meetingCategory;
    }

    /**
     * Returns meeting category id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meetingCategory")
     *
     * @return string
     */
    public function getMeetingCategoryId()
    {
        return $this->meetingCategory ? $this->meetingCategory->getId() : null;
    }

    /**
     * Returns meeting category name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meetingCategoryName")
     *
     * @return string
     */
    public function getMeetingCategoryName()
    {
        return $this->meetingCategory ? $this->meetingCategory->getName() : null;
    }

    /**
     * Add meetingObjective.
     *
     * @param MeetingObjective $meetingObjective
     *
     * @return Meeting
     */
    public function addMeetingObjective(MeetingObjective $meetingObjective)
    {
        $this->meetingObjectives[] = $meetingObjective;
        $meetingObjective->setMeeting($this);
    }

    /**
     * Remove meetingObjective.
     *
     * @param MeetingObjective $meetingObjective
     */
    public function removeMeetingObjective(MeetingObjective $meetingObjective)
    {
        $this->meetingObjectives->removeElement($meetingObjective);
    }

    /**
     * Get meetingObjective.
     *
     * @return Collection
     */
    public function getMeetingObjectives()
    {
        return $this->meetingObjectives;
    }

    /**
     * @return int
     */
    public function getMeetingAgendasTotalDuration()
    {
        $totalDuration = 0;

        foreach ($this->getMeetingAgendas() as $agenda) {
            $totalDuration += $agenda->getDuration();
        }

        return $totalDuration;
    }

    /**
     * @return int
     */
    public function getMeetingDuration()
    {
        return (new DateRange($this->getStart(), $this->getEnd()))->getDurationMinutes();
    }

    /**
     * Add meetingReport.
     *
     * @param MeetingReport $meetingReport
     */
    public function addMeetingReport(MeetingReport $meetingReport)
    {
        $this->meetingReports[] = $meetingReport;
    }

    /**
     * Remove meetingReport.
     *
     * @param MeetingReport $meetingReport
     */
    public function removeMeetingReport(MeetingReport $meetingReport)
    {
        $this->meetingReports->removeElement($meetingReport);
    }

    /**
     * Get meetingReports.
     *
     * @return Collection
     */
    public function getMeetingReports()
    {
        return $this->meetingReports;
    }


    /**
     * Set link to Jitsi-meeting
     *
     * @param string $host
     */
    public function setJitsiLink(string $host = '')
    {
        $workspaceName = !empty($host) ? explode('.', $host)[0] : '';
        $workspaceName = str_replace('-', 0, $workspaceName);
        $workspaceId = $this->getProject()->getProjectUsers()->current()->getUser()->getTeams()->current()->getId();
        $projectId = $this->getPojectId();
        $distributionListId = $this->getDistributionLists()->current()->getId();

        $this->jitsiLink = "https://jitsi.campr.biz/{$workspaceName}{$workspaceId}{$projectId}{$this->id}{$distributionListId}";
    }
}
