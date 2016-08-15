<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="responsibility_id")
     */
    private $responsibility;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="time", nullable=false)
     */
    private $start;

    /**
     * @var \DateTime
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
     * @param \AppBundle\Entity\Meeting $meeting
     *
     * @return MeetingAgenda
     */
    public function setMeeting(\AppBundle\Entity\Meeting $meeting = null)
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * Get meeting.
     *
     * @return \AppBundle\Entity\Meeting
     */
    public function getMeeting()
    {
        return $this->meeting;
    }

    /**
     * Set responsibility.
     *
     * @param \AppBundle\Entity\User $responsibility
     *
     * @return MeetingAgenda
     */
    public function setResponsibility(\AppBundle\Entity\User $responsibility = null)
    {
        $this->responsibility = $responsibility;

        return $this;
    }

    /**
     * Get responsibility.
     *
     * @return \AppBundle\Entity\User
     */
    public function getResponsibility()
    {
        return $this->responsibility;
    }
}
