<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Communication.
 *
 * @ORM\Table(name="communication")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommunicationRepository")
 */
class Communication
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
     * @ORM\Column(name="meeting_name", type="string", length=255)
     */
    private $meetingName;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinTable(
     *     name="communication_participant",
     *     joinColumns={
     *         @ORM\JoinColumn(name="communication_id")
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(name="user_id")
     *     }
     * )
     */
    private $participants;

    /**
     * @var Schedule
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Schedule")
     * @ORM\JoinColumn(name="schedule_id")
     */
    private $schedule;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255)
     */
    private $location;

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
     * Communication constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->participants = new ArrayCollection();
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
     * Set meetingName.
     *
     * @param string $meetingName
     *
     * @return Communication
     */
    public function setMeetingName($meetingName)
    {
        $this->meetingName = $meetingName;

        return $this;
    }

    /**
     * Get meetingName.
     *
     * @return string
     */
    public function getMeetingName()
    {
        return $this->meetingName;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Communication
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
     * Set location.
     *
     * @param string $location
     *
     * @return Communication
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Communication
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
     * @return Communication
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
     * @return Communication
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
     * Add participant.
     *
     * @param User $participant
     *
     * @return Communication
     */
    public function addParticipant(User $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * Remove participant.
     *
     * @param User $participant
     */
    public function removeParticipant(User $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * Get participants.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * Set schedule.
     *
     * @param Schedule $schedule
     *
     * @return Communication
     */
    public function setSchedule(Schedule $schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * Get schedule.
     *
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meetingName")
     *
     * @return string
     */
    public function getMeetingNameSerialized()
    {
        return $this->meetingName ? $this->meetingName : null;
    }
}
