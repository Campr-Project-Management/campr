<?php

namespace Component\Snapshot\Transformer;

interface TransformerInterface
{
    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function transform($object);

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool;
}
