<?php

namespace Component\Model\Opportunity;

trait OpportunityPriorityTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=255, nullable=false)
     */
    protected $priority;

    /**
     * @return string|int|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int|string|null $priority
     */
    public function setPriority($priority = null)
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
}
