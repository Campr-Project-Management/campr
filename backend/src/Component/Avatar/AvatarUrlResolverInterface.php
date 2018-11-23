<?php

namespace Component\Avatar;

use Component\Avatar\Model\AvatarAwareInterface;

interface AvatarUrlResolverInterface
{
    /**
     * @param AvatarAwareInterface $object
     *
     * @return string
     */
    public function resolve(AvatarAwareInterface $object): string;
}
