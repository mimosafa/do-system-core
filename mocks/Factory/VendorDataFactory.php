<?php

namespace DoSystemMock\Factory;

use Faker\Factory;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class VendorDataFactory
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * Status int values
     *
     * @var int[]
     */
    private $statusIntValues;

    /**
     * Constructor
     *
     * @access private
     */
    private function __construct()
    {
        $this->faker = Factory::create('ja_JP');
        $this->statusIntValues = \array_map(function ($vo) {
            return $vo->getValue();
        }, VendorValueStatus::values());
    }

    /**
     * Singleton
     *
     * @access private
     */
    private static function instance(): self
    {
        /**
         * @var self|null
         */
        static $instance;
        return $instance ?? $instance = new self();
    }

    /**
     * Fake data array for creating vendor generator
     *
     * @access public
     *
     * @return array
     */
    public static function generate(): array
    {
        $instance = self::instance();

        $data = [];
        $data['name'] = $instance->faker->company;
        $data['status'] = $instance->faker->randomElement($instance->statusIntValues);

        return $data;
    }
}
