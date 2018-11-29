<?php

namespace Component\User\Model;

interface UserAwareInterface
{
    /**
     * @return UserInterface|null
     */
    public function getUser();

    /**
     * @param UserInterface|null $user
     */
    public function setUser(UserInterface $user = null);
}
