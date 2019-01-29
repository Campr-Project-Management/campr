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
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $finish
     */
    public function __construct(\DateTimeInterface $start = null, \DateTimeInterface $finish = null)
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
        $end = $this->getFinish() ?? new \DateTime();
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

    /**
     * @return int
     */
    public function getDurationMinutes(): int
    {
        if (!$this->getStart() || !$this->getFinish() || $this->getStart() > $this->getFinish()) {
            return 0;
        }

        return ($this->getFinish()->getTimestamp() - $this->getStart()->getTimestamp()) / 60;
    }
}
