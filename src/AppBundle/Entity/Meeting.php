<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="time")
     */
    private $start;

    /**
     * @var \DateTime
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
     * @var ArrayCollection|Document[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Document", mappedBy="meetings")
     */
    private $documents;

    /**
     * @var ArrayCollection|Decision[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Decision", mappedBy="meeting")
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
     * Constructor.
     */
    public function __construct()
    {
        $this->meetingParticipants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->meetingAgendas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->decisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->todos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setDate($date)
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
    public function setStart($start)
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
    public function setEnd($end)
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
     * @param \AppBundle\Entity\Project $project
     *
     * @return Meeting
     */
    public function setProject(\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return \AppBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add meetingParticipant.
     *
     * @param \AppBundle\Entity\MeetingParticipant $meetingParticipant
     *
     * @return Meeting
     */
    public function addMeetingParticipant(\AppBundle\Entity\MeetingParticipant $meetingParticipant)
    {
        $this->meetingParticipants[] = $meetingParticipant;

        return $this;
    }

    /**
     * Remove meetingParticipant.
     *
     * @param \AppBundle\Entity\MeetingParticipant $meetingParticipant
     */
    public function removeMeetingParticipant(\AppBundle\Entity\MeetingParticipant $meetingParticipant)
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
     * @param \AppBundle\Entity\MeetingAgenda $meetingAgenda
     *
     * @return Meeting
     */
    public function addMeetingAgenda(\AppBundle\Entity\MeetingAgenda $meetingAgenda)
    {
        $this->meetingAgendas[] = $meetingAgenda;

        return $this;
    }

    /**
     * Remove meetingAgenda.
     *
     * @param \AppBundle\Entity\MeetingAgenda $meetingAgenda
     */
    public function removeMeetingAgenda(\AppBundle\Entity\MeetingAgenda $meetingAgenda)
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
     * Add document.
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Meeting
     */
    public function addDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document.
     *
     * @param \AppBundle\Entity\Document $document
     */
    public function removeDocument(\AppBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Add decision.
     *
     * @param \AppBundle\Entity\Decision $decision
     *
     * @return Meeting
     */
    public function addDecision(\AppBundle\Entity\Decision $decision)
    {
        $this->decisions[] = $decision;

        return $this;
    }

    /**
     * Remove decision.
     *
     * @param \AppBundle\Entity\Decision $decision
     */
    public function removeDecision(\AppBundle\Entity\Decision $decision)
    {
        $this->decisions->removeElement($decision);
    }

    /**
     * Get decisions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDecisions()
    {
        return $this->decisions;
    }

    /**
     * Add todo.
     *
     * @param \AppBundle\Entity\Todo $todo
     *
     * @return Meeting
     */
    public function addTodo(\AppBundle\Entity\Todo $todo)
    {
        $this->todos[] = $todo;

        return $this;
    }

    /**
     * Remove todo.
     *
     * @param \AppBundle\Entity\Todo $todo
     */
    public function removeTodo(\AppBundle\Entity\Todo $todo)
    {
        $this->todos->removeElement($todo);
    }

    /**
     * Get todos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * Add note.
     *
     * @param \AppBundle\Entity\Note $note
     *
     * @return Meeting
     */
    public function addNote(\AppBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note.
     *
     * @param \AppBundle\Entity\Note $note
     */
    public function removeNote(\AppBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
