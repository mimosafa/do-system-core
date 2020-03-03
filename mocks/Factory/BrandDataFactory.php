<?php

namespace DoSystemMock\Factory;

use Faker\Factory;
use DoSystem\Domain\Brand\Model\BrandValueStatus;

class BrandDataFactory
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
        }, BrandValueStatus::values());
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
     * Generate fake data array for creating brand
     *
     * @access public
     *
     * @param int $vendorId
     * @return array
     */
    public static function generate(int $vendorId): array
    {
        $instance = self::instance();

        $data = [];
        $data['vendor_id'] = $vendorId;
        $data['name'] = $instance->faker->lastName . $instance->faker->randomElement(['屋', '亭', '庵']);
        $data['status'] = $instance->faker->randomElement($instance->statusIntValues);

        return $data;
    }
}
