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

    public function setRandomApiTokenIfEmailDoesNotMatch($email, $apiToken)
    {
        $this
            ->getEntityManager()
            ->getConnection()
            ->executeUpdate(
                'UPDATE user SET api_token = UUID() WHERE email != ? AND api_token = ?',
                [$email, $apiToken]
            )
        ;
    }
}
