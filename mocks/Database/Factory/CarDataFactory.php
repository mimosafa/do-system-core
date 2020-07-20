<?php

namespace DoSystemCoreMock\Database\Factory;

use DoSystem\Core\Domain\Car\CarValueStatus;
use DoSystem\Core\Domain\Car\CarValueVin;

class CarDataFactory extends AbstractDataFactory
{
    /**
     * @var int[]
     */
    private $statuses;

    /**
     * @var string regex
     */
    private $vinPattern;

    /**
     * Constructor
     */
    protected function __construct()
    {
        parent::__construct();
        $this->statuses = \array_map(function ($valueObject) {
            return $valueObject->getValue();
        }, CarValueStatus::values());
        $this->vinPattern = CarValueVin::getRegexPattern();
    }

    /**
     * Generate fake data array for creating car
     *
     * @access public
     *
     * @param int[] $vendorIds
     * @return array
     */
    public static function generate(array $vendorIds): array
    {
        $instance = self::instance();
        $nameSafix = $instance->faker->randomElement(['', '号', '車']);

        return [
            'vendor_id' => $instance->faker->randomElement($vendorIds),
            'vin'       => $instance->faker->regexify($instance->vinPattern),
            'status'    => $instance->faker->randomElement($instance->statuses),
            'name'      => $nameSafix ? $instance->faker->lastName . $nameSafix : '',
            'order'     => $instance->faker->randomElement([null, 0, 1, 2, 3]),
        ];
    }
}
