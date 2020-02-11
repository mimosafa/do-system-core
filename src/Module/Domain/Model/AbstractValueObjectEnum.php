<?php

namespace DoSystem\Module\Domain\Model;

use BadMethodCallException;
use Illuminate\Support\Str;
use ReflectionClass;
use UnexpectedValueException;

/**
 * Inspired by MyCLab\Enum\Enum
 * @link http://github.com/myclabs/php-enum
 *
 * @example DoSystem\Module\Mock\Model\SampleValueObjectEnum
 */
abstract class AbstractValueObjectEnum
{
    use ValueObjectTrait;

    /**
     * @var int
     */
    protected $value;

    /**
     * Specific labels string for enum keys
     *
     * @var array
     */
    protected $labels = [];

    /**
     * @var array
     */
    protected static $cache = [];

    /**
     * Constructor
     *
     * @param int $value
     * @throws UnexpectedValueException
     */
    public function __construct(int $value)
    {
        if (!static::isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum " . \get_called_class());
        }
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return static::search($this->value);
    }

    /**
     * @uses Str::title()
     *
     * @return string
     */
    public function getLabel(): string
    {
        $value = $this->getValue();
        return $this->labels[$value] ?? Str::title($this->getKey());
    }

    /**
     * @static
     *
     * @param mixed $value
     * @return bool
     */
    public static function isValid($value): bool
    {
        return \in_array($value, static::toArray(), true);
    }

    /**
     * @static
     *
     * @param mixed $key
     * @return bool
     */
    public static function isValidKey($key): bool
    {
        return \array_key_exists($key, static::toArray());
    }

    /**
     * @static
     *
     * @return array
     */
    public static function toArray(): array
    {
        $class = \get_called_class();
        if (!isset(static::$cache[$class])) {
            $reflection = new ReflectionClass($class);
            static::$cache[$class] = $reflection->getConstants();
        }
        return static::$cache[$class];
    }

    /**
     * @static
     *
     * @return static[]
     */
    public static function values(): array
    {
        $values = [];
        foreach (static::toArray() as $key => $value) {
            $values[$key] = static::of($value);
        }
        return $values;
    }

    /**
     * @static
     *
     * @return string
     */
    public static function search(int $value): string
    {
        return \array_search($value, static::toArray(), true);
    }

    /**
     * Magic method
     *
     * Checks that value of the object and
     * value corresponding to the called key are equal
     * @method bool is{$StudlyCasedKey}()
     * @throws BadMethodCallException
     *
     * @uses Str::snake()
     */
    public function __call($name, $arguments)
    {
        if (\preg_match('/^is(.+)$/', $name, $match)) {
            $key = \strtoupper(Str::snake($match[1]));
            if (static::isValidKey($key)) {
                return $this->equals(static::of(static::toArray()[$key]));
            }
        }
        throw new BadMethodCallException("No method constant '$name' in class " . \get_called_class());
    }
}
