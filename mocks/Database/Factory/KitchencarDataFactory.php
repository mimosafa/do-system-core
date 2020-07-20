<?php

namespace DoSystemMock\Database\Factory;

class KitchencarDataFactory extends AbstractDataFactory
{
    /**
     * @var array
     */
    private $orders = [null, 0, 1, 2, 3];

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
        $done = false;

        // While loop for undefined index error
        // No idea why it occurs ...
        while (!$done) {
            $vendorId  = $instance->faker->randomElement($vendorIds);

            if (!isset($idsMap[$vendorId]['brand']) || empty($idsMap[$vendorId]['brand'])) {
                continue;
            }
            $brandId = $instance->faker->randomElement($idsMap[$vendorId]['brand']);

            if (!isset($idsMap[$vendorId]['car']) || empty($idsMap[$vendorId]['car'])) {
                continue;
            }
            $carId = $instance->faker->randomElement($idsMap[$vendorId]['car']);

            $done = true;
        }

        return [
            'brand_id' => $brandId,
            'car_id'   => $carId,
            'order'    => $instance->faker->randomElement($instance->orders),
        ];
    }
}
