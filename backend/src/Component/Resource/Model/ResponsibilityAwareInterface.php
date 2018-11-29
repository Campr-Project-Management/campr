<?php

namespace Component\Resource\Model;

use Component\User\Model\UserInterface;

interface ResponsibilityAwareInterface
{
    /**
     * @return UserInterface|null
     */
    public function getResponsibility();
}
