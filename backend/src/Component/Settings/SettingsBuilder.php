<?php

namespace Component\Settings;

use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingsBuilder implements SettingsBuilderInterface
{
    /**
     * @var OptionsResolver
     */
    private $resolver;

    public function __construct()
    {
        $this->resolver = new OptionsResolver();
    }

    /**
     * @param array $defaults
     *
     * @return SettingsBuilderInterface
     */
    public function setDefaults(array $defaults): SettingsBuilderInterface
    {
        $this->resolver->setDefaults($defaults);

        return $this;
    }

    /**
     * @param array $types
     *
     * @return SettingsBuilderInterface
     */
    public function setAllowedTypes(array $types): SettingsBuilderInterface
    {
        foreach ($types as $key => $values) {
            $this->resolver->setAllowedTypes($key, $values);
        }

        return $this;
    }

    /**
     * @param string $option
     *
     * @return bool
     */
    public function isDefined(string $option): bool
    {
        return $this->resolver->isDefined($option);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function resolve(array $parameters): array
    {
        return $this->resolver->resolve($parameters);
    }

    /**
     * @param array $values
     *
     * @return SettingsBuilderInterface
     */
    public function setAllowedValues(array $values): SettingsBuilderInterface
    {
        foreach ($values as $name => $value) {
            $this->resolver->setAllowedValues($name, $value);
        }

        return $this;
    }
}
