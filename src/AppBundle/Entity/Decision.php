<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Decision.
 *
 * @ORM\Table(name="decision")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DecisionRepository")
 */
class Decision
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
     * @var Meeting|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="decisions")
     * @ORM\JoinColumn(name="meeting_id")
     */
    private $meeting;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="show_in_status_report", type="boolean", nullable=false, options={"default"=0})
     */
    private $showInStatusReport = false;

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
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="due_date", type="date", nullable=true)
     */
    private $dueDate;

    /**
     * @var Status
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Status")
     * @ORM\JoinColumn(name="status_id")
     */
    private $status;

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
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * Decision constructor.
     */
    public function __construct()
    {
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
     * Set title.
     *
     * @param string $title
     *
     * @return Decision
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
     * @return Decision
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
     * Set showInStatusReport.
     *
     * @param bool $showInStatusReport
     *
     * @return Decision
     */
    public function setShowInStatusReport($showInStatusReport)
    {
        $this->showInStatusReport = $showInStatusReport;

        return $this;
    }

    /**
     * Get showInStatusReport.
     *
     * @return bool
     */
    public function getShowInStatusReport()
    {
        return $this->showInStatusReport;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Decision
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
     * Set dueDate.
     *
     * @param \DateTime $dueDate
     *
     * @return Decision
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
     * @return Decision
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
     * @return Decision
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
     * Set project.
     *
     * @param Project $project
     *
     * @return Decision
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
     * Set meeting.
     *
     * @param Meeting $meeting
     *
     * @return Decision
     */
    public function setMeeting(Meeting $meeting = null)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting.
     *
     * @return Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return Decision
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
     * Set status.
     *
     * @param Status $status
     *
     * @return Decision
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns project name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("project")
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->project ? $this->project->getName() : null;
    }

    /**
     * Returns the responsibility username.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("responsibility")
     *
     * @return string
     */
    public function getResponsibilityName()
    {
        return $this->responsibility ? $this->responsibility->getUsername() : null;
    }

    /**
     * Returns date formatted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("date")
     *
     * @return string
     */
    public function getDateFormatted()
    {
        return $this->date ? $this->date->format('d/m/Y') : null;
    }

    /**
     * Returns dueDate formatted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("dueDate")
     *
     * @return string
     */
    public function getDueDateFormatted()
    {
        return $this->dueDate ? $this->dueDate->format('d/m/Y') : null;
    }
}
