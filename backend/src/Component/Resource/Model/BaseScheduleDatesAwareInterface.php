<?php

namespace Component\Resource\Model;

interface BaseScheduleDatesAwareInterface
{
    /**
     * @return \DateTime
     */
    public function getScheduledStartAt();

    /**
     * @return \DateTime
     */
    public function getScheduledFinishAt();
}
