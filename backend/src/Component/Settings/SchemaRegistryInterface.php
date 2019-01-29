<?php

namespace Component\Settings;

interface SchemaRegistryInterface
{
    /**
     * @param string          $name
     * @param SchemaInterface $schema
     */
    public function register(string $name, SchemaInterface $schema);

    /**
     * @param string $name
     *
     * @return SchemaInterface|null
     */
    public function get(string $name);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @return array
     */
    public function all(): array;
}
