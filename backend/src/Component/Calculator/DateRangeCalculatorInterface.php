<?php

namespace Component\Calculator;

interface DateRangeCalculatorInterface
{
    /**
     * @param \DateTime $start
     * @param \DateTime $end
     *
     * @return mixed
     */
    public function calculate(\DateTime $start, \DateTime $end);
}
