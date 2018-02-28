<?php

namespace Component\TimeUnit;

interface TimeUnitAwareInterface
{
    const MONTHS = 'choices.months';

    const WEEKS = 'choices.weeks';

    const DAYS = 'choices.days';

    const HOURS = 'choices.hours';

    /**
     * @return string
     */
    public function getTimeUnit(): string;

    /**
     * @param string $timeUnit
     */
    public function setTimeUnit(string $timeUnit);
}
