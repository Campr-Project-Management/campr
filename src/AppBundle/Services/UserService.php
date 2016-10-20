<?php

namespace AppBundle\Services;

class UserService
{
    public function generateActivationResetToken()
    {
        return substr(md5(microtime()), rand(0, 26), 6);
    }
}
