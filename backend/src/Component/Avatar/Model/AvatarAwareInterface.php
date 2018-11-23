<?php

namespace Component\Avatar\Model;

interface AvatarAwareInterface
{
    /**
     * @return string
     */
    public function getAvatarUrl();
}
