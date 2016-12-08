<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * MeetingAgenda.
 *
 * @ORM\Table(name="meeting_agenda")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingAgendaRepository")
 */
class MeetingAgenda
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
     * @var Meeting
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="meetingAgendas")
     * @ORM\JoinColumn(name="meeting_id")
     */
    private $meeting;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=255)
     */
    private $topic;

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
     * @ORM\Column(name="start", type="time", nullable=false)
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Serializer\Exclude()
     *
     * @ORM\Column(name="end", type="time", nullable=false)
     */
    private $end;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="time", nullable=false)
     */
    private $duration;

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
     * Set topic.
     *
     * @param string $topic
     *
     * @return MeetingAgenda
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic.
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set start.
     *
     * @param \DateTime $start
     *
     * @return MeetingAgenda
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
     * Returns start date formattted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("start")
     *
     * @return string
     */
    public function getStartFormatted()
    {
        return $this->start ? $this->start->format('H:i') : null;
    }

    /**
     * Set end.
     *
     * @param \DateTime $end
     *
     * @return MeetingAgenda
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
     * Returns end date formatted.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("end")
     *
     * @return string
     */
    public function getEndFormatted()
    {
        return $this->end ? $this->end->format('H:i') : null;
    }

    /**
     * Set duration.
     *
     * @param \DateTime $duration
     *
     * @return MeetingAgenda
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration.
     *
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set meeting.
     *
     * @param Meeting $meeting
     *
     * @return MeetingAgenda
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
     * Returns meeting name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meeting")
     *
     * @return string
     */
    public function getMeetingName()
    {
        return $this->meeting ? $this->meeting->getName() : null;
    }

    /**
     * Set responsibility.
     *
     * @param User $responsibility
     *
     * @return MeetingAgenda
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
     * Returns responsibility username.
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
}
