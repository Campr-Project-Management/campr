<?php

namespace Component\User;

use AppBundle\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class UserUploadedAvatarUrlResolver implements UserAvatarUrlResolverInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UserUploadedAvatarUrlResolver constructor.
     *
     * @param StorageInterface $storage
     * @param RouterInterface  $router
     */
    public function __construct(StorageInterface $storage, RouterInterface $router)
    {
        $this->storage = $storage;
        $this->router = $router;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function resolve(User $user): string
    {
        return (string) $this->storage->resolveUri($user, 'avatarFile');
    }
}
