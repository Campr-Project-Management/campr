<?php

namespace Component\Uploader\Resolver;

interface UrlResolverInterface
{
    /**
     * @param mixed $resource
     *
     * @return string
     */
    public function resolve($resource): string;
}
