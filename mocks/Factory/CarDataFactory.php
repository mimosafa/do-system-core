<?php

namespace DoSystemMock\Factory;

use Faker\Factory;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;

class CarDataFactory
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
        }, CarValueStatus::values());
    }

    /**
     * Singleton
     *
     * @access private
     *
     * @return self
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
     * Generate fake data array for creating car
     *
     * @access public
     *
     * @param int $vendorId
     * @return array
     */
    public static function generate(int $vendorId): array
    {
        $instance = self::instance();
        $vinPattern = CarValueVin::getRegexPattern();
        $nameSafix = $instance->faker->randomElement(['', '号', '車']);

        $data = [];
        $data['vendor_id'] = $vendorId;
        $data['vin'] = $instance->faker->regexify($vinPattern);
        $data['status'] = $instance->faker->randomElement($instance->statusIntValues);
        $data['name'] = $nameSafix ? $instance->faker->lastName . $nameSafix : '';

        return $data;
    }
}
