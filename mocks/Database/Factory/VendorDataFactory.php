<?php

namespace DoSystemMock\Database\Factory;

use DoSystem\Core\Domain\Vendor\VendorValueStatus;

class VendorDataFactory extends AbstractDataFactory
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
        }, VendorValueStatus::values());
    }

    /**
     * Generate fake data array for creating vendor
     *
     * @access public
     *
     * @return array
     */
    public static function generate(): array
    {
        $instance = self::instance();

        return [
            'name'   => $instance->faker->company,
            'status' => $instance->faker->randomElement($instance->statuses),
        ];
    }
}
