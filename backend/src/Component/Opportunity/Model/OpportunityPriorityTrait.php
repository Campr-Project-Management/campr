<?php

namespace Component\Opportunity\Model;

trait OpportunityPriorityTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="impact", type="integer")
     */
    private $impact;

    /**
     * @var int
     *
     * @ORM\Column(name="probability", type="integer")
     */
    private $probability;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    protected $priority;

    /**
     * @return int
     */
    public function getPriority()
    {
        return (int) $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority = null)
    {
        $this->priority = $priority;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getPriorityName(): string
    {
        $names = [
            static::PRIORITY_VERY_LOW => 'very_low',
            static::PRIORITY_LOW => 'low',
            static::PRIORITY_MEDIUM => 'medium',
            static::PRIORITY_HIGH => 'high',
            static::PRIORITY_VERY_HIGH => 'very_high',
        ];

        return $names[$this->getPriority()] ?? '';
    }

    /**
     * Set impact.
     *
     * @param int $impact
     */
    public function setImpact($impact)
    {
        $this->impact = $impact;
    }

    /**
     * Get impact.
     *
     * @return int
     */
    public function getImpact()
    {
        return $this->impact;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int
     */
    public function getImpactIndex()
    {
        $thresholds = [
            static::THRESHOLD_VERY_HIGH,
            static::THRESHOLD_HIGH,
            static::THRESHOLD_LOW,
            static::THRESHOLD_VERY_LOW,
        ];
        foreach ($thresholds as $index => $threshold) {
            if ($this->getImpact() >= $threshold[0] && $this->getImpact() < $threshold[1]) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * @param int $probability
     */
    public function setProbability($probability)
    {
        $this->probability = $probability;
    }

    /**
     * Get probability.
     *
     * @return float
     */
    public function getProbability()
    {
        return $this->probability;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return int
     */
    public function getProbabilityIndex()
    {
        $thresholds = [
            static::THRESHOLD_VERY_LOW,
            static::THRESHOLD_LOW,
            static::THRESHOLD_HIGH,
            static::THRESHOLD_VERY_HIGH,
        ];
        foreach ($thresholds as $index => $threshold) {
            if ($this->getProbability() >= $threshold[0] && $this->getProbability() < $threshold[1]) {
                return $index;
            }
        }

        return 0;
    }
}
