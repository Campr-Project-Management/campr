<?php

namespace Component\Settings;

use Webmozart\Assert\Assert;

class SchemaRegistry implements SchemaRegistryInterface
{
    /**
     * @var array
     */
    private $registry = [];

    /**
     * @param string          $name
     * @param SchemaInterface $schema
     */
    public function register(string $name, SchemaInterface $schema)
    {
        Assert::false($this->has($name), 'Schema already registered');

        $this->registry[$name] = $schema;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->registry;
    }

    /**
     * @param string $name
     *
     * @return SchemaInterface|null
     */
    public function get(string $name)
    {
        return $this->registry[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->registry[$name]);
    }
}
