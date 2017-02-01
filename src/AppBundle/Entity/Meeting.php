<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

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
     * @ORM\ManyToOne(targetEntity="Project")
     * @ORM\JoinColumn(name="project_id")
     */
    private $project;

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
     * @Serializer\Type("DateTime<'H:i:s'>")
     *
     * @ORM\Column(name="start", type="time")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Serializer\Type("DateTime<'H:i:s'>")
     *
     * @ORM\Column(name="end", type="time")
     */
    private $end;

    /**
     * @var string
     *
     * @ORM\Column(name="objectives", type="text")
     */
    private $objectives;

    /**
     * @var ArrayCollection|MeetingParticipant[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingParticipant", mappedBy="meeting")
     */
    private $meetingParticipants;

    /**
     * @var ArrayCollection|MeetingAgenda[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MeetingAgenda", mappedBy="meeting")
     */
    private $meetingAgendas;

    /**
     * @var ArrayCollection|Media[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Media", mappedBy="meetings")
     */
    private $medias;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany   (targetEntity="AppBundle\Entity\Decision", mappedBy="meeting")
     */
    private $decisions;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Todo", mappedBy="meeting")
     */
    private $todos;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="meeting")
     */
    private $notes;

    /**
     * @var ArrayCollection|DistributionList[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\DistributionList", mappedBy="meetings")
     */
    private $distributionLists;

    /**
     * Meeting constructor.
     */
    public function __construct()
    {
        $this->meetingParticipants = new ArrayCollection();
        $this->meetingAgendas = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->decisions = new ArrayCollection();
        $this->todos = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->distributionLists = new ArrayCollection();
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
     * Set objectives.
     *
     * @param string $objectives
     *
     * @return Meeting
     */
    public function setObjectives($objectives)
    {
        $this->objectives = $objectives;

        return $this;
    }

    /**
     * Get objectives.
     *
     * @return string
     */
    public function getObjectives()
    {
        return $this->objectives;
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
     * Add note.
     *
     * @param Note $note
     *
     * @return Meeting
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note.
     *
     * @param Note $note
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes.
     *
     * @return ArrayCollection|Note[]
     */
    public function getNotes()
    {
        return $this->notes;
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
}
