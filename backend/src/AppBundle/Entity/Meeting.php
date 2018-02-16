<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Meeting.
 *
 * @ORM\Table(name="meeting")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingRepository")
 */
class Meeting
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
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="meetings")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

    /**
     * @var MeetingCategory|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MeetingCategory")
     * @ORM\JoinColumn(name="meeting_category_id")
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
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'H:i'>")
     *
     * @ORM\Column(name="end", type="time")
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Decision", mappedBy="meeting", cascade={"all"})
     */
    private $decisions;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="meeting", cascade={"all"})
     */
    private $todos;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Info", mappedBy="meeting", cascade={"all"})
     */
    private $infos;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="meetings", cascade={"all"})
     */
    private $distributionLists;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="ownedMeetings")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
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
     * Meeting constructor.
     */
    public function __construct()
    {
        $this->meetingParticipants = new ArrayCollection();
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

        return $this;
    }

    /**
     * Remove meetingParticipant.
     *
     * @param MeetingParticipant $meetingParticipant
     */
    public function removeMeetingParticipant(MeetingParticipant $meetingParticipant)
    {
        $this->meetingParticipants->removeElement($meetingParticipant);
    }

    /**
     * Get meetingParticipants.
     *
     * @return \Doctrine\Common\Collections\Collection
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

        return $this;
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
     * @return \Doctrine\Common\Collections\Collection
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
        $this->medias[] = $media;
        $media->addMeeting($this);

        return $this;
    }

    /**
     * Remove media.
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
        $media->removeMeeting($this);

        return $this;
    }

    /**
     * Get medias.
     *
     * @return ArrayCollection
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

        return $this;
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

        return $this;
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
        $this->distributionLists[] = $distributionList;
        $distributionList->addMeeting($this);

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
        $distributionList->removeMeeting($this);

        return $this;
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Meeting
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
     * @return Meeting
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
    public function setMeetingCategory(MeetingCategory $meetingCategory)
    {
        $this->meetingCategory = $meetingCategory;

        return $this;
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

        return $this;
    }

    /**
     * Remove meetingObjective.
     *
     * @param MeetingObjective $meetingObjective
     */
    public function removeMeetingObjective(MeetingObjective $meetingObjective)
    {
        $this->meetingObjectives->removeElement($meetingObjective);

        return $this;
    }

    /**
     * Get meetingObjective.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMeetingObjectives()
    {
        return $this->meetingObjectives;
    }
}
