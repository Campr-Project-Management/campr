<?php

namespace Component\Resource\Model;

trait CodeableTrait
{
    /**
     * @var string|null
     */
    protected $code;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return (string) $this->code;
    }

    /**
     * @param string|null $code
     *
     * @return $this
     */
    public function setCode(string $code = null)
    {
        $this->code = $code;

        return $this;
    }
}
