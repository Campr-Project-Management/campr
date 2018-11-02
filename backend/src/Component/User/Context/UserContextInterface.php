<?php

namespace Component\User\Context;

use AppBundle\Entity\User;

interface UserContextInterface
{
    /**
     * @return User
     */
    public function getUser();
}
