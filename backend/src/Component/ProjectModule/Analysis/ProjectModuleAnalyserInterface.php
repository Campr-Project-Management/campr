<?php

namespace Component\ProjectModule\Analysis;

interface ProjectModuleAnalyserInterface
{
    /**
     * @param string $module
     * @param int    $value
     * @param string $type
     *
     * @return bool
     */
    public function analyse(string $module, int $value, string $type = null): bool;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsType(string $type): bool;

    /**
     * @return string
     */
    public function getType(): string;
}
