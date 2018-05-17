<?php

namespace Component\Project\Calculator;

class WorkPackageTrafficLightTotal
{
    /**
     * @var int
     */
    private $red;

    /**
     * @var int
     */
    private $yellow;

    /**
     * @var int
     */
    private $green;

    /**
     * @return int
     */
    public function getRed(): int
    {
        return $this->red;
    }

    /**
     * @param int $red
     */
    public function setRed(int $red): void
    {
        $this->red = $red;
    }

    /**
     * @return int
     */
    public function getYellow(): int
    {
        return $this->yellow;
    }

    /**
     * @param int $yellow
     */
    public function setYellow(int $yellow): void
    {
        $this->yellow = $yellow;
    }

    /**
     * @return int
     */
    public function getGreen(): int
    {
        return $this->green;
    }

    /**
     * @param int $green
     */
    public function setGreen(int $green): void
    {
        $this->green = $green;
    }
}
