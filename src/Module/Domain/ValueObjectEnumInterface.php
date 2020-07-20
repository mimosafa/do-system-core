<?php

namespace DoSystem\Core\Module\Domain;

interface ValueObjectEnumInterface extends ValueObjectInterface
{
    /**
     * @return int
     */
    public function getValue(): int;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @static
     *
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool;

    /**
     * @static
     *
     * @param mixed $key
     * @return bool
     */
    public static function isValidKey($key): bool;

    /**
     * @static
     *
     * @return array
     */
    public static function toArray(): array;

    /**
     * @static
     *
     * @return static[]
     */
    public static function values(): array;
}
