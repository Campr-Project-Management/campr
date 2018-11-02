<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;

class UserRepository extends BaseRepository
{
    /**
     * @param string $token
     *
     * @return User|null
     */
    public function findOneByActivationToken($token)
    {
        /** @var User $user */
        $user = $this->findOneBy(
            [
                'activationToken' => $token,
            ]
        );

        return $user;
    }
}
