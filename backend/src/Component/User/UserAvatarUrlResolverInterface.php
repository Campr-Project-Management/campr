<?php

namespace Component\User;

use AppBundle\Entity\User;

interface UserAvatarUrlResolverInterface
{
    /**
     * @param User $user
     *
     * @return string
     */
    public function resolve(User $user): string;
}
