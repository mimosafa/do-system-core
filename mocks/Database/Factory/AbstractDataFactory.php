<?php

namespace DoSystemCoreMock\Database\Factory;

use Faker\Factory;

abstract class AbstractDataFactory
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var self[]
     */
    protected static $instances = [];

    /**
     * Constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        $this->faker = Factory::create('ja_JP');
    }

    /**
     * Singleton
     *
     * @static
     * @access protected
     */
    protected static function instance(): self
    {
        $class = \get_called_class();
        return self::$instances[$class] ?? self::$instances[$class] = new static();
    }
}
