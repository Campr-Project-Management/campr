<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * WorkingTime.
 *
 * @ORM\Table(name="working_time")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkingTimeRepository")
 */
class WorkingTime
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
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'H:i:s'>")
     *
     * @ORM\Column(name="from_time", type="time", nullable=true)
     */
    private $fromTime;

    /**
     * @var \DateTime|null
     *
     * @Serializer\Type("DateTime<'H:i:s'>")
     *
     * @ORM\Column(name="to_time", type="time", nullable=true)
     */
    private $toTime;

    /**
     * @var Day|null
     *
     * @Serializer\Exclude()
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Day", inversedBy="workingTimes")
     * @ORM\JoinColumn(name="day_id")
     */
    private $day;

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
     * Set fromTime.
     *
     * @param \DateTime $fromTime
     *
     * @return WorkingTime
     */
    public function setFromTime(\DateTime $fromTime = null)
    {
        $this->fromTime = $fromTime;

        return $this;
    }

    /**
     * Get fromTime.
     *
     * @return \DateTime
     */
    public function getFromTime()
    {
        return $this->fromTime;
    }

    /**
     * Set toTime.
     *
     * @param \DateTime $toTime
     *
     * @return WorkingTime
     */
    public function setToTime(\DateTime $toTime = null)
    {
        $this->toTime = $toTime;

        return $this;
    }

    /**
     * Get toTime.
     *
     * @return \DateTime
     */
    public function getToTime()
    {
        return $this->toTime;
    }

    /**
     * Set day.
     *
     * @param Day $day
     *
     * @return WorkingTime
     */
    public function setDay(Day $day = null)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day.
     *
     * @return Day
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Returns day id.
     *
     * @Serializer\VirtualProperty()
     * @Serializer\SerializedName("day")
     *
     * @return int
     */
    public function getDayId()
    {
        return $this->day ? $this->day->getId() : null;
    }
}
