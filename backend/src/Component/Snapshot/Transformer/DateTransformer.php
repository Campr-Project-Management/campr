<?php

namespace Component\Snapshot\Transformer;

class DateTransformer extends AbstractTransformer
{
    /**
     * @param \DateTime $object
     *
     * @return string|null
     */
    protected function doTransform($object)
    {
        if ($object instanceof \DateTime) {
            return $object->format('Y-m-d');
        }

        return null;
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function support($object): bool
    {
        return is_null($object) || ($object instanceof \DateTime);
    }
}
