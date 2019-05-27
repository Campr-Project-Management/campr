<?php

namespace Component\Avatar;

use Component\Avatar\Model\AvatarAwareInterface;
use Component\Uploader\Resolver\UrlResolverInterface;

class UploadedAvatarUrlResolver implements AvatarUrlResolverInterface
{
    /**
     * @var UrlResolverInterface
     */
    private $urlResolver;

    /**
     * UserUploadedAvatarUrlResolver constructor.
     *
     * @param UrlResolverInterface $urlResolver
     */
    public function __construct(UrlResolverInterface $urlResolver)
    {
        $this->urlResolver = $urlResolver;
    }

    /**
     * @param AvatarAwareInterface $user
     *
     * @return string
     */
    public function resolve(AvatarAwareInterface $user): string
    {
        return $this->urlResolver->resolve($user);
    }
}
