<?php

namespace DoSystemMock\Database\Factory;

class KitchencarDataFactory extends AbstractDataFactory
{
    /**
     * @var array
     */
    private $orders = [null, 1, 2, 3];

    /**
     * Generate fake data
     *
     * @param array $idsMap
     * @return array
     */
    public static function generate(array $idsMap): array
    {
        $instance = self::instance();

        $vendorIds = \array_keys($idsMap);
        $vendorId  = $instance->faker->randomElement($vendorIds);
        $brandId   = $instance->faker->randomElement($idsMap[$vendorId]['brand']);
        $carId     = $instance->faker->randomElement($idsMap[$vendorId]['car']);

        return [
            'brand_id' => $brandId,
            'car_id'   => $carId,
            'order'    => $instance->faker->randomElement($instance->orders),
        ];
    }
}
