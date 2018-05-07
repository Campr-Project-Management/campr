<?php

namespace Component\Snapshot\Transformer;

use Webmozart\Assert\Assert;

abstract class AbstractTransformer implements TransformerInterface
{
    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function transform($object)
    {
        Assert::true($this->support($object), sprintf('Object "%s" not supported', get_class($object)));

        return $this->doTransform($object);
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    abstract protected function doTransform($object);
}
