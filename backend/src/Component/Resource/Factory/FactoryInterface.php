<?php

namespace Component\Resource\Factory;

interface FactoryInterface
{
    /**
     * @return object
     */
    public function createNew();
}
