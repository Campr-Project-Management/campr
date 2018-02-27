<?php

namespace Component\TimeUnit;

use Webmozart\Assert\Assert;

final class TimeUnitsConvertor
{
    /**
     * @var TimeUnitAwareInterface
     */
    private $timeUnitAware;

    /**
     * TimeUnitsConvertor constructor.
     *
     * @param TimeUnitAwareInterface $timeUnitAware
     */
    public function __construct(TimeUnitAwareInterface $timeUnitAware)
    {
        $this->timeUnitAware = $timeUnitAware;
    }

    /**
     * @param float $amount
     *
     * @return float
     */
    public function toHours(float $amount): float
    {
        $unit = $this->timeUnitAware->getTimeUnit();

        $data = [
            TimeUnitAwareInterface::HOURS => $amount,
            TimeUnitAwareInterface::MONTHS => $amount * 30 * 24,
            TimeUnitAwareInterface::WEEKS => $amount * 7 * 24,
            TimeUnitAwareInterface::DAYS => $amount * 24,
        ];

        Assert::keyExists($data, $unit, sprintf('Unknown time unit: %s', $unit));

        return $data[$unit];
    }
}
