<?php

namespace Component\Resource\Model;

trait SequenceableTrait
{
    /**
     * @var int|null
     */
    protected $sequence;

    /**
     * Set sequence.
     *
     * @param int $sequence
     *
     * @return $this
     */
    public function setSequence(int $sequence = null)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Get sequence.
     *
     * @return int|null
     */
    public function getSequence()
    {
        return $this->sequence;
    }
}
