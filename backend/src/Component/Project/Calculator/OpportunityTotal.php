<?php

namespace Component\Project\Calculator;

class OpportunityTotal
{
    /**
     * @var float
     */
    private $potentialCost;

    /**
     * @var float
     */
    private $potentialTime;

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

    /**
     * @return float
     */
    public function getPotentialTime(): float
    {
        return $this->potentialTime;
    }

    /**
     * @param float $potentialTime
     */
    public function setPotentialTime(float $potentialTime): void
    {
        $this->potentialTime = $potentialTime;
    }
}
