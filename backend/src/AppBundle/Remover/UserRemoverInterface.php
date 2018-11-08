<?php

namespace AppBundle\Remover;

use AppBundle\Entity\User;

interface UserRemoverInterface
{
    /**
     * @param User $user
     */
    public function remove(User $user);
}
