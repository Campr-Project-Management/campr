<?php

namespace AppBundle\Services\Authentication;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use AppBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
    private $em;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadUserByApiToken($apiToken)
    {
        return $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'activationToken' => $apiToken,
            ])
        ;
    }

    public function loadUserByUsername($username)
    {
        return $this
            ->em
            ->getRepository(User::class)
            ->findOneBy([
                'email' => $username,
            ])
        ;
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return \Symfony\Component\Security\Core\User\User::class === $class;
    }
}
