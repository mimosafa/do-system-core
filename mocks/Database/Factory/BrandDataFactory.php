<?php

namespace DoSystemMock\Database\Factory;

use DoSystem\Core\Domain\Brand\BrandValueStatus;

class BrandDataFactory extends AbstractDataFactory
{
    /**
     * @var int[]
     */
    private $statuses;

    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct();
        $this->statuses = \array_map(function ($valueObject) {
            return $valueObject->getValue();
        }, BrandValueStatus::values());
    }

    /**
     * Generate fake data array for creating brand
     *
     * @access public
     *
     * @param int[] $vendorIds
     * @return array
     */
    public static function generate(array $vendorIds): array
    {
        $instance = self::instance();
        $nameSafix = $instance->faker->randomElement(['屋', '亭', '庵']);

        return [
            'vendor_id' => $instance->faker->randomElement($vendorIds),
            'name'      => $instance->faker->lastName . $nameSafix,
            'status'    => $instance->faker->randomElement($instance->statuses),
            'order'     => $instance->faker->randomElement([null, 1, 2, 3]),
        ];
    }
}
