<?php

namespace DoSystemCoreMock\Module\Domain\Model;

use DoSystem\Core\Module\Domain\AbstractValueObjectString;
use DoSystem\Core\Module\Domain\ValueObjectStringInterface;

class SampleValueObjectString extends AbstractValueObjectString implements ValueObjectStringInterface
{
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
     * @var bool
     */
    protected static $multibyte = true;

    /**
     * @var bool
     */
    protected static $allowEmpty = true;

    /**
     * Default parameters for init tests
     */
    private static $defaultParams = [
        'minLength'  => null,
        'maxLength'  => null,
        'pattern'    => null,
        'multibyte'  => true,
        'allowEmpty' => true,
    ];

    /**
     * Set static properties for tests
     *
     * @param string $key
     * @param mixed $property
     * @return void
     */
    public static function set(string $key, $property): void
    {
        if (\property_exists(self::class, $key) && $key !== 'defaultParams') {
            self::${$key} = $property;
        }
    }

    /**
     * Init static properties for tests
     *
     * @return void
     */
    public static function init(): void
    {
        foreach (self::$defaultParams as $key => $value) {
            self::${$key} = $value;
        }
    }
}
