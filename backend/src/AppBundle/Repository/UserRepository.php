<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Webmozart\Assert\Assert;

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

    public function setRandomUUIDIfEmailDoesNotMatch($email, $uuid)
    {
        $this
            ->getEntityManager()
            ->getConnection()
            ->executeUpdate(
                'UPDATE user SET `uuid` = UUID() WHERE email != ? AND `uuid` = ?',
                [$email, $uuid]
            )
        ;
    }

    public function countAdminsExcept(User $user): int
    {
        Assert::notNull($user->getId());

        $qb = $this->createQueryBuilder('u');
        $qb->select('COUNT(u.id)');
        $qb->where(
            $qb->expr()->andX(
                $qb->expr()->neq('u.id', $user->getId()),
                $qb->expr()->orX(
                    $qb->expr()->like('u.roles', $qb->expr()->literal('%ROLE_ADMIN%')),
                    $qb->expr()->like('u.roles', $qb->expr()->literal('%ROLE_SUPER_ADMIN%'))
                )
            )
        );

        return $qb->getQuery()->getSingleScalarResult();
    }
}
