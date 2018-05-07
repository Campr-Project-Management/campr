<?php

namespace Component\Cost\Graph;

use JMS\Serializer\Annotation as Serializer;

class CostGraphData
{
    /**
     * @Serializer\Exclude()
     *
     * @var array
     */
    private $data = [];

    /**
     * @param string $name
     * @param float  $value
     */
    public function setActual(string $name, float $value = null)
    {
        $this->setValue($name, 'actual', $value);
    }

    /**
     * @param string $name
     * @param float  $value
     */
    public function setBase(string $name, float $value = null)
    {
        $this->setValue($name, 'base', $value);
    }

    /**
     * @param string $name
     * @param float  $value
     */
    public function setForecast(string $name, float $value = null)
    {
        $this->setValue($name, 'forecast', $value);
    }

    /**
     * @param string $name
     * @param float  $value
     */
    public function setRemaining(string $name, float $value = null)
    {
        $this->setValue($name, 'remaining', $value);
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\Inline()
     *
     * @return array
     */
    public function getData(): array
    {
        return array_values($this->data);
    }

    /**
     * @param string $name
     * @param string $key
     * @param float  $value
     */
    private function setValue(string $name, string $key, float $value = null)
    {
        $this->init($name);

        $this->data[$name]['values'][$key] = $value;
    }

    /**
     * @param string $name
     */
    private function init(string $name)
    {
        if (isset($this->data[$name])) {
            return;
        }

        $this->data[$name] = [
            'name' => $name,
            'values' => [
                'actual' => 0,
                'base' => 0,
                'forecast' => 0,
                'remaining' => -300,
            ],
        ];
    }
}
