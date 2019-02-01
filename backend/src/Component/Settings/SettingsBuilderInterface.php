<?php

namespace Component\Settings;

interface SettingsBuilderInterface
{
    /**
     * @param array $defaults
     *
     * @return SettingsBuilderInterface
     */
    public function setDefaults(array $defaults): SettingsBuilderInterface;

    /**
     * @param array $types
     *
     * @return SettingsBuilderInterface
     */
    public function setAllowedTypes(array $types): SettingsBuilderInterface;

    /**
     * @param array $values
     *
     * @return SettingsBuilderInterface
     */
    public function setAllowedValues(array $values): SettingsBuilderInterface;

    /**
     * @param string $option
     *
     * @return bool
     */
    public function isDefined(string $option): bool;

    /**
     * @param array $parameters
     *
     * @return array
     */
    public function resolve(array $parameters): array;
}
