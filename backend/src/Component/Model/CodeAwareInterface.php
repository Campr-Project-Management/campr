<?php

namespace Component\Model;

interface CodeAwareInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param string|null $code
     */
    public function setCode(string $code = null);
}
