<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * MeetingParticipant.
 *
 * @ORM\Table(name="meeting_participant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MeetingParticipantRepository")
 */
class MeetingParticipant
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Meeting", inversedBy="meetingParticipants")
     * @ORM\JoinColumn(name="meeting_id", onDelete="CASCADE")
     */
    private $meeting;

    /**
     * @var User
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="string", length=255, nullable=true)
     */
    private $remark;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_present", type="boolean", nullable=false, options={"default"=0})
     */
    private $isPresent = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_excused", type="boolean", nullable=false, options={"default"=0})
     */
    private $isExcused = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="in_distribution_list", type="boolean", nullable=false, options={"default"=0})
     */
    private $inDistributionList = false;

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
     * Set remark.
     *
     * @param string $remark
     *
     * @return MeetingParticipant
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark.
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set isPresent.
     *
     * @param bool $isPresent
     *
     * @return MeetingParticipant
     */
    public function setIsPresent($isPresent)
    {
        $this->isPresent = $isPresent;

        return $this;
    }

    /**
     * Get isPresent.
     *
     * @return bool
     */
    public function getIsPresent()
    {
        return $this->isPresent;
    }

    /**
     * Set isExcused.
     *
     * @param bool $isExcused
     *
     * @return MeetingParticipant
     */
    public function setIsExcused($isExcused)
    {
        $this->isExcused = $isExcused;

        return $this;
    }

    /**
     * Get isExcused.
     *
     * @return bool
     */
    public function getIsExcused()
    {
        return $this->isExcused;
    }

    /**
     * Set inDistributionList.
     *
     * @param bool $inDistributionList
     *
     * @return MeetingParticipant
     */
    public function setInDistributionList($inDistributionList)
    {
        $this->inDistributionList = $inDistributionList;

        return $this;
    }

    /**
     * Get inDistributionList.
     *
     * @return bool
     */
    public function getInDistributionList()
    {
        return $this->inDistributionList;
    }

    /**
     * Set meeting.
     *
     * @param Meeting $meeting
     *
     * @return MeetingParticipant
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
     * Set user.
     *
     * @param User $user
     *
     * @return MeetingParticipant
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Returns user id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("user")
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user ? $this->user->getId() : null;
    }

    /**
     * Returns user full name.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userFullName")
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->user ? $this->user->getFullName() : null;
    }

    /**
     * Returns user departments.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("userDepartmentNames")
     *
     * @return string
     */
    public function getUserDepartmentNames()
    {
        if ($this->user) {
            foreach ($this->user->getProjectUsers() as $projectUser) {
                if (!$this->getMeeting()) {
                    continue;
                }

                if ($projectUser->getProject() === $this->getMeeting()->getProject()) {
                    return $projectUser->getProjectDepartmentNames();
                }
            }
        }

        return [];
    }
}
