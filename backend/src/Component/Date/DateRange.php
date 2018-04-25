<?php

namespace Component\Date;

class DateRange implements DateRangeInterface
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $finish;

    /**
     * DateRange constructor.
     *
     * @param \DateTime|null $start
     * @param \DateTime|null $finish
     */
    public function __construct(\DateTime $start = null, \DateTime $finish = null)
    {
        $this->start = $start;
        $this->finish = $finish;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateTime|null
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @return int
     */
    public function getDurationDays(): int
    {
        $start = $this->getStart();
        $end = $this->getFinish();
        if (!$end) {
            $end = new \DateTime();
        }

        if (!$start || $start > $end) {
            return 0;
        }

        $start = clone $start;
        $end = clone $end;
        $start->setTime(0, 0, 0);
        $end->setTime(23, 59, 59);

        $interval = $end->diff($start);

        return (int) $interval->format('%a') + 1;
    }
}
