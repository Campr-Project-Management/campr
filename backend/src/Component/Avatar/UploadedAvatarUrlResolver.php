<?php

namespace Component\Avatar;

use Component\Avatar\Model\AvatarAwareInterface;
use Symfony\Component\Routing\RouterInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class UploadedAvatarUrlResolver implements AvatarUrlResolverInterface
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
     * @param AvatarAwareInterface $user
     *
     * @return string
     */
    public function resolve(AvatarAwareInterface $user): string
    {
        $avatar = (string) $this->storage->resolveUri($user, 'avatarFile');

        if (empty($avatar)) {
            return $avatar;
        }

        return sprintf(
            '%s://%s%s',
            $this->router->getContext()->getScheme(),
            $this->router->getContext()->getHost(),
            $avatar
        );
    }
}
