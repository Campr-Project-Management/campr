<?php

namespace Component\Resource\Model;

use Component\User\Model\UserInterface;

interface BlameableInterface
{
    /**
     * @return UserInterface|null
     */
    public function getCreatedBy();

    /**
     * @return UserInterface|null
     */
    public function getUpdatedBy();
}
