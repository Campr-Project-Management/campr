<?php

namespace Component\Cost\Calculator;

use Component\TrafficLight\TrafficLight;

class ProjectTotalCost
{
    /**
     * @var float
     */
    private $internal = [
        'base' => 0,
        'forecast' => 0,
        'actual' => 0,
    ];

    /**
     * @var float
     */
    private $external = [
        'base' => 0,
        'forecast' => 0,
        'actual' => 0,
    ];

    /**
     * @param float $base
     */
    public function setInternalBase(float $base)
    {
        $this->internal['base'] = $base;
    }

    /**
     * @return float
     */
    public function getInternalBase(): float
    {
        return $this->internal['base'];
    }

    /**
     * @param float $forecast
     */
    public function setInternalForecast(float $forecast)
    {
        $this->internal['forecast'] = $forecast;
    }

    /**
     * @return float
     */
    public function getInternalForecast(): float
    {
        return $this->internal['forecast'];
    }

    /**
     * @param float $actual
     */
    public function setInternalActual(float $actual)
    {
        $this->internal['actual'] = $actual;
    }

    /**
     * @return float
     */
    public function getInternalActual(): float
    {
        return $this->internal['actual'];
    }

    /**
     * @param float $base
     */
    public function setExternalBase(float $base)
    {
        $this->external['base'] = $base;
    }

    /**
     * @return float
     */
    public function getExternalBase(): float
    {
        return $this->external['base'];
    }

    /**
     * @param float $forecast
     */
    public function setExternalForecast(float $forecast)
    {
        $this->external['forecast'] = $forecast;
    }

    /**
     * @return float
     */
    public function getExternalForecast(): float
    {
        return $this->external['forecast'];
    }

    /**
     * @param float $actual
     */
    public function setExternalActual(float $actual)
    {
        $this->external['actual'] = $actual;
    }

    /**
     * @return float
     */
    public function getExternalActual(): float
    {
        return $this->external['actual'];
    }

    /**
     * @return float
     */
    public function getBase(): float
    {
        return $this->internal['base'] + $this->external['base'];
    }

    /**
     * @return float
     */
    public function getActual(): float
    {
        return $this->internal['actual'] + $this->external['actual'];
    }

    /**
     * @return float
     */
    public function getForecast(): float
    {
        return $this->internal['forecast'] + $this->external['forecast'];
    }

    /**
     * @return float
     */
    public function getProgress(): float
    {
        $forecast = $this->getForecast();
        if (empty($forecast)) {
            return 0;
        }

        return round(($this->getActual() / $forecast) * 100, 2);
    }

    /**
     * @return int
     */
    public function getInternalTrafficLight(): int
    {
        return $this->calculateTrafficLight(
            $this->getInternalBase(),
            $this->getInternalForecast(),
            $this->getInternalActual()
        );
    }

    /**
     * @return int
     */
    public function getExternalTrafficLight(): int
    {
        return $this->calculateTrafficLight(
            $this->getExternalBase(),
            $this->getExternalForecast(),
            $this->getExternalActual()
        );
    }

    /**
     * @return int
     */
    public function getTrafficLight(): int
    {
        return $this->calculateTrafficLight($this->getBase(), $this->getForecast(), $this->getActual());
    }

    /**
     * @param float $base
     * @param float $forecast
     * @param float $actual
     *
     * @return int
     */
    private function calculateTrafficLight(float $base, float $forecast, float $actual): int
    {
        if ($actual > $forecast) {
            return TrafficLight::RED;
        }

        if ($forecast > $base) {
            return TrafficLight::YELLOW;
        }

        return TrafficLight::GREEN;
    }
}
