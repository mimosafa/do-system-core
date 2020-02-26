<?php

namespace DoSystemMock\Factory;

use Faker\Factory;
use Illuminate\Support\Str;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;

class VendorsFactory
{
    /**
     * @var Factory
     */
    private $faker;

    /**
     * @var int
     */
    private $numberOfRows;

    /**
     * Generated fake vendor data
     *
     * @var array[]
     */
    private $fakeData = [];

    /**
     * Generated data counts for tests
     */
    private $counts = [
        'name'   => ['株式会社' => 0, '有限会社' => 0],
        'status' => [],
    ];

    /**
     * Status int values
     *
     * @static
     * @var int[]
     */
    private static $vendorStatusIntValues;

    /**
     * Constructor
     *
     * @param int $numberOfRows
     */
    public function __construct(int $numberOfRows)
    {
        $this->faker = Factory::create('ja_JP');
        $this->numberOfRows = $numberOfRows;
        $this->prepareData();
    }

    /**
     * @return array[]
     */
    public function provide(): array
    {
        return $this->fakeData;
    }

    /**
     * @return int
     */
    public function countByName株式会社(): int
    {
        return $this->counts['name']['株式会社'];
    }
    public function countByName有限会社(): int
    {
        return $this->counts['name']['有限会社'];
    }

    /**
     * @return int
     */
    public function countByStatus(int $status): int
    {
        return $this->counts['status'][$status] ?? 0;
    }

    /**
     * @return void
     */
    private function prepareData(): void
    {
        for ($i = 0; $i < $this->numberOfRows; $i++) {
            // name
            $name = $this->faker->company;
            /**
             * @see https://github.com/fzaninotto/Faker/blob/v1.9.1/src/Faker/Provider/ja_JP/Company.php
             */
            if (Str::contains($name, '株式会社')) {
                $this->counts['name']['株式会社']++;
            }
            else if (Str::contains($name, '有限会社')) {
                $this->counts['name']['有限会社']++;
            }

            // status
            $status = $this->faker->randomElement(self::getVendorStatusIntValues());
            if (!isset($this->counts['status'][$status])) {
                $this->counts['status'][$status] = 0;
            }
            $this->counts['status'][$status]++;

            $this->fakeData[] = [
                'name' => $name,
                'status' => $status,
            ];
        }
    }

    /**
     * @return int[]
     */
    private static function getVendorStatusIntValues(): array
    {
        if (self::$vendorStatusIntValues === null) {
            $valueObjects = VendorValueStatus::values();
            self::$vendorStatusIntValues = \array_map(function ($o) {
                return $o->getValue();
            }, $valueObjects);
        }
        return self::$vendorStatusIntValues;
    }
}
