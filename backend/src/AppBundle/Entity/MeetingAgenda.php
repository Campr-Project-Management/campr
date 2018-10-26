<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @ORM\JoinColumn(name="meeting_id", onDelete="CASCADE")
     */
    private $meeting;

    /**
     * @var string
     *
     * @ORM\Column(name="topic", type="string", length=255)
     */
    private $topic;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="integer", nullable=false, options={"default"=0})
     */
    private $duration = 0;

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
     * @Serializer\Type("DateTime<'H:i'>")
     *
     * @ORM\Column(name="start", type="time", nullable=false)
     */
    private $start;

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
     * Returns meeting id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meeting")
     *
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meeting ? $this->meeting->getId() : null;
    }

    /**
     * Returns meeting name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("meetingName")
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
    public function getResponsibilityId()
    {
        return $this->responsibility ? $this->responsibility->getId() : null;
    }

    /**
     * Returns responsibility username.
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
     * Set duration.
     *
     * @param int $duration
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
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Validation for agenda duration, the sum of the duration of all the agendas
     * cannot excede the duration of the meeting.
     *
     * @Assert\Callback
     *
     * @param ExecutionContextInterface $context
     */
    public function meetingAgendaDurationValidator(ExecutionContextInterface $context)
    {
        if (is_null($this->getMeeting())) {
            return;
        }

        $agendasIDS = [];
        $agendas = $this->getMeeting()->getMeetingAgendas();
        $futureDuration = $this->getMeeting()->getMeetingAgendasTotalDuration();

        foreach ($agendas as $agenda) {
            array_push($agendasIDS, $agenda->getId());
        }

        if (!in_array($this->getId(), $agendasIDS)) {
            $futureDuration += $this->getDuration();
        }

        if ($futureDuration > $this->getMeeting()->getMeetingDuration()) {
            $context
                ->buildViolation(
                    'is_greater_than.meeting_duration',
                    [
                        '%duration%' => $this->getMeeting()->getMeetingDuration(),
                    ]
                )
                ->atPath('duration')
                ->addViolation()
            ;
        }
    }
}
