<?php

namespace AppBundle\Remover;

use AppBundle\Entity\User;
use Component\Repository\RepositoryInterface;

class UserRemover implements UserRemoverInterface
{
    /**
     * @var RepositoryInterface
     */
    private $userRepository;

    /**
     * UserRemover constructor.
     *
     * @param RepositoryInterface $userRepository
     */
    public function __construct(RepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     */
    public function remove(User $user)
    {
        $user->setDeletedAt(new \DateTime());
        $user->setFirstName('Deleted');
        $user->setLastName('User');
        $user->setUsername($this->generateFakeUsername());
        $user->setEmail($this->generateFakeEmail());
        $user->setEnabled(false);
        $user->setSuspended(true);
        $user->setAvatarUrl(null);
        $user->setGoogleAuthenticatorSecret(0);
        $user->setActivationToken(null);
        $user->setGplus(null);
        $user->setInstagram(null);
        $user->setFacebook(null);
        $user->setLinkedIn(null);
        $user->setMedium(null);
        $user->setTwitter(null);
        $user->setSignUpDetails([]);
        $user->setPhone(null);
        $user->setTrustedComputers([]);
        $user->setPlainPassword(uniqid());

        $this->userRepository->add($user);
    }

    /**
     * @return string
     */
    private function generateFakeUsername(): string
    {
        return uniqid('deleted_');
    }

    /**
     * @return string
     */
    private function generateFakeEmail(): string
    {
        return implode('@', [uniqid('deleted_'), 'campr.biz']);
    }
}
