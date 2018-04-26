<?php

namespace Component\Date;

interface DateRangeInterface
{
    /**
     * @return \DateTime|null
     */
    public function getStart();

    /**
     * @return \DateTime|null
     */
    public function getFinish();

    /**
     * @return int
     */
    public function getDurationDays(): int;
}
