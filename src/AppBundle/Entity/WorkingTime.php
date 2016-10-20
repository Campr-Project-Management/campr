<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="from_time", type="time", nullable=true)
     */
    private $fromTime;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="to_time", type="time", nullable=true)
     */
    private $toTime;

    /**
     * @var Day|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Day")
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
    public function setFromTime(\DateTime $fromTime)
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
    public function setToTime(\DateTime $toTime)
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
}
