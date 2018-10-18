<?php

namespace Component\ProjectModule\Analysis;

use Component\ProjectModule\ProjectModules;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

abstract class AbstractProjectModuleAnalyser implements ProjectModuleAnalyserInterface
{
    /**
     * @var ExpressionLanguage
     */
    private static $expressionLanguage;

    /**
     * @param string $module
     * @param int    $value
     * @param string $type
     *
     * @return bool
     */
    public function analyse(string $module, int $value, string $type = null): bool
    {
        if ($type && !$this->supportsType($type)) {
            throw new \InvalidArgumentException(sprintf('Type "%d" is not supported', $type));
        }

        if (!$this->supportsModule($module)) {
            throw new \InvalidArgumentException(sprintf('Module "%s" is not supported', $module));
        }

        if (!$this->supportsValue($value)) {
            throw new \InvalidArgumentException(sprintf('Value "%d" is not supported', $value));
        }

        $criterias = $this->getCriterias($module);

        return $this->evaluateCriterias($criterias, $value);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supportsType(string $type): bool
    {
        return $this->getType() === $type;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    abstract protected function getCriterias(string $module): array;

    /**
     * @param int $value
     *
     * @return bool
     */
    protected function supportsValue(int $value): bool
    {
        return $value >= 0;
    }

    /**
     * @param array $criterias
     * @param int   $value
     *
     * @return bool
     */
    protected function evaluateCriterias(array $criterias, int $value): bool
    {
        if (empty($criterias)) {
            return false;
        }

        foreach ($criterias as $expr => $result) {
            if ($this->evaluate($expr, ['value' => $value])) {
                return $result;
            }
        }

        return false;
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    protected function supportsModule(string $module): bool
    {
        return in_array($module, ProjectModules::MODULES);
    }

    /**
     * @param string $expr
     * @param array  $values
     *
     * @return bool
     */
    protected function evaluate(string $expr, array $values): bool
    {
        $el = self::getExpressionLangauge();

        return $el->evaluate($expr, $values);
    }

    /**
     * @return ExpressionLanguage
     */
    private static function getExpressionLangauge(): ExpressionLanguage
    {
        if (!self::$expressionLanguage) {
            self::$expressionLanguage = new ExpressionLanguage();
        }

        return self::$expressionLanguage;
    }
}
