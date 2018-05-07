<?php

namespace Component\Project\Calculator;

class RiskTotal
{
    /**
     * @var float
     */
    private $potentialCost;

    /**
     * @var float
     */
    private $potentialDelay;

    /**
     * @var int
     */
    private $measuresCount;

    /**
     * @var float
     */
    private $measuresCost;

    /**
     * @return float
     */
    public function getPotentialCost(): float
    {
        return $this->potentialCost;
    }

    /**
     * @param float $potentialCost
     */
    public function setPotentialCost(float $potentialCost): void
    {
        $this->potentialCost = $potentialCost;
    }

    /**
     * @return float
     */
    public function getPotentialDelay(): float
    {
        return $this->potentialDelay;
    }

    /**
     * @param float $potentialDelay
     */
    public function setPotentialDelay(float $potentialDelay): void
    {
        $this->potentialDelay = $potentialDelay;
    }

    /**
     * @return int
     */
    public function getMeasuresCount(): int
    {
        return $this->measuresCount;
    }

    /**
     * @param int $measuresCount
     */
    public function setMeasuresCount(int $measuresCount): void
    {
        $this->measuresCount = $measuresCount;
    }

    /**
     * @return float
     */
    public function getMeasuresCost(): float
    {
        return $this->measuresCost;
    }

    /**
     * @param float $measuresCost
     */
    public function setMeasuresCost(float $measuresCost): void
    {
        $this->measuresCost = $measuresCost;
    }
}
