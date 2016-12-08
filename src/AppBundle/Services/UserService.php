<?php

namespace AppBundle\Services;

/**
 * Class UserService
 * Service used to handle user related actions.
 */
class UserService
{
    /**
     * Returns a random token for user activation/reset.
     *
     * @return mixed
     */
    public function generateActivationResetToken()
    {
        return substr(md5(microtime()), rand(0, 26), 6);
    }
}
