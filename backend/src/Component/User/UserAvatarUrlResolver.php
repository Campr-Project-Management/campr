<?php

namespace Component\User;

use AppBundle\Entity\User;

class UserAvatarUrlResolver implements UserAvatarUrlResolverInterface
{
    /**
     * @var
     */
    private $uploadedAvatarResolver;

    /**
     * @var string
     */
    private $gravatarBaseUrl;

    /**
     * UserAvatarUrlResolver constructor.
     *
     * @param UserAvatarUrlResolverInterface $uploadedAvatarResolver
     * @param string                         $gravatarBaseUrl
     */
    public function __construct(UserAvatarUrlResolverInterface $uploadedAvatarResolver, string $gravatarBaseUrl)
    {
        $this->uploadedAvatarResolver = $uploadedAvatarResolver;
        $this->gravatarBaseUrl = $gravatarBaseUrl;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function resolve(User $user): string
    {
        $avatar = $user->getAvatar();
        if (empty($avatar)) {
            return $this->getGravatar($user);
        }

        return $this->getAvatar($user);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getAvatar(User $user): string
    {
        return (string) $this->uploadedAvatarResolver->resolve($user);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function getGravatar(User $user): string
    {
        $email = md5(strtolower(trim($user->getEmail())));

        return sprintf('%s/%s?d=identicon', $this->gravatarBaseUrl, $email);
    }
}
