<?php

namespace Component\Settings;

interface SettingsInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get(string $name);

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters);

    /**
     * @return array
     */
    public function all(): array;
}
