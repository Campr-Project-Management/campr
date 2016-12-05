<?php

namespace AppBundle\Repository;

class UserRepository extends BaseRepository
{
    public function findOneByActivationToken($token)
    {
        return $this->findOneBy([
            'activationToken' => $token,
        ]);
    }
}
