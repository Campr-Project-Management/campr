<?php

namespace Component\TrafficLight;

use Webmozart\Assert\Assert;

final class TrafficLight
{
    const RED = 0;

    const YELLOW = 1;

    const GREEN = 2;

    /**
     * @var array
     */
    private static $codes = [
        self::RED => 'red',
        self::YELLOW => 'yellow',
        self::GREEN => 'green',
    ];

    /**
     * @var int
     */
    private $value;

    /**
     * TrafficLight constructor.
     *
     * @param int $value
     */
    public function __construct(int $value)
    {
        Assert::keyExists(self::$codes, $value);

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return self::$codes[$this->value];
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return TrafficLight
     */
    public static function createRed(): self
    {
        return new self(self::RED);
    }

    /**
     * @return TrafficLight
     */
    public static function createYellow(): self
    {
        return new self(self::RED);
    }

    /**
     * @return TrafficLight
     */
    public static function createGreen(): self
    {
        return new self(self::GREEN);
    }

    /**
     * @return int[]
     */
    public static function getValues(): array
    {
        return array_keys(self::$codes);
    }
}
