<?php

namespace Component\Avatar\Model;

interface GravatarAwareInterface
{
    /**
     * @return string
     */
    public function getEmail();
}
