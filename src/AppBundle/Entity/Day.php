<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Day.
 *
 * @ORM\Table(name="day")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DayRepository")
 */
class Day
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
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="working", type="integer", nullable=false)
     */
    private $working;

    /**
     * @var ArrayCollection|WorkingTime[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkingTime", mappedBy="day")
     */
    private $workingTimes;

    /**
     * @var Calendar|null
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Calendar", inversedBy="days", cascade={"persist"})
     * @ORM\JoinColumn(name="calendar_id")
     */
    private $calendar;

    public function __construct()
    {
        $this->workingTimes = new ArrayCollection();
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
     * Set type.
     *
     * @param int $type
     *
     * @return Day
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set working.
     *
     * @param int $working
     *
     * @return Day
     */
    public function setWorking($working)
    {
        $this->working = $working;

        return $this;
    }

    /**
     * Get working.
     *
     * @return int
     */
    public function getWorking()
    {
        return $this->working;
    }

    /**
     * Add workingTime.
     *
     * @param WorkingTime $workingTime
     *
     * @return Day
     */
    public function addWorkingTime(WorkingTime $workingTime)
    {
        $this->workingTimes[] = $workingTime;

        return $this;
    }

    /**
     * Remove workingTime.
     *
     * @param WorkingTime $workingTime
     */
    public function removeWorkingTime(WorkingTime $workingTime)
    {
        $this->workingTimes->removeElement($workingTime);
    }

    /**
     * Get workingTimes.
     *
     * @return ArrayCollection
     */
    public function getWorkingTimes()
    {
        return $this->workingTimes;
    }

    /**
     * Set calendar.
     *
     * @param Calendar $calendar
     *
     * @return Day
     */
    public function setCalendar(Calendar $calendar = null)
    {
        $this->calendar = $calendar;

        return $this;
    }

    /**
     * Get calendar.
     *
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }
}
