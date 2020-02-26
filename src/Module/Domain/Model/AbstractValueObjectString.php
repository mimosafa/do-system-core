<?php

namespace DoSystem\Module\Domain\Model;

abstract class AbstractValueObjectString
{
    use ValueObjectTrait;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected static $minLength;

    /**
     * @var int
     */
    protected static $maxLength;

    /**
     * The regexp pattern for string
     *
     * @var string
     */
    protected static $pattern;

    /**
     * Acceptable value list
     *
     * @var string[]
     */
    protected static $list = [];

    /**
     * @var bool
     */
    protected static $multibyte = true;

    /**
     * @var bool
     */
    protected static $allowEmpty = true;

    /**
     * Constructor
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!static::isValid($value)) {
            throw new \Exception();
        }
        $this->value = $value;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        if (!\is_string($value)) {
            return false;
        }
        if (!static::$allowEmpty && !$value) {
            return false;
        }
        if (!empty(static::$list) && !\in_array($value, static::$list, true)) {
            return false;
        }
        else {
            if (!static::$multibyte && (\strlen($value) !== \mb_strlen($value))) {
                return false;
            }
            if (static::$pattern && !\preg_match(static::$pattern, $value)) {
                return false;
            }
            if (isset(static::$minLength) && static::strlen($value) < static::$minLength) {
                return false;
            }
            if (isset(static::$maxLength) && static::strlen($value) > static::$maxLength) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param mixed $valueObject
     * @return bool
     */
    public function equals($valueObject): bool
    {
        return $valueObject instanceof static
            && $this->getValue() === $valueObject->getValue()
            && \get_called_class() === \get_class($valueObject);
    }

    /**
     * @param string $str
     * @return int
     */
    protected static function strlen(string $str) {
        return static::$multibyte ? \mb_strlen($str) : \strlen($str);
    }
}
