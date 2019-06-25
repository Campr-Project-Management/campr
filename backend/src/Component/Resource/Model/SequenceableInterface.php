<?php

namespace Component\Resource\Model;

interface SequenceableInterface
{
    /**
     * @param int $sequence
     *
     * @return mixed
     */
    public function setSequence(int $sequence);

    /**
     * @return int
     */
    public function getSequence();
}
