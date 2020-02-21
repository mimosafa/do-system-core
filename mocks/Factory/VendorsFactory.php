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
    private $factory;

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
     * Number of companies whose name contains '株式会社'/'有限会社'
     *
     * @see https://github.com/fzaninotto/Faker/blob/v1.9.1/src/Faker/Provider/ja_JP/Company.php
     * @var int
     */
    private $kabushikigaishaNum = 0;
    private $yuugengaishaNum = 0;

    /**
     * @var array
     */
    private $statusNum = [];

    /**
     * Status int values
     *
     * @var int[]
     */
    private static $vendorStatusValues;

    /**
     * Constructor
     *
     * @param int $numberOfRows
     */
    public function __construct(int $numberOfRows)
    {
        $this->factory = Factory::create('ja_JP');
        $this->numberOfRows = $numberOfRows;
        if (self::$vendorStatusValues === null) {
            $valueObjects = VendorValueStatus::values();
            self::$vendorStatusValues = \array_map(function ($o) {
                return $o->getValue();
            }, $valueObjects);
        }
        $this->prepareData();
    }

    /**
     * @param int $numRow
     * @return array[]
     */
    public function provide(): array
    {
        return $this->fakeData;
    }

    /**
     * @return int[]
     */
    public function vendorIds(): array
    {
        return \array_column($this->fakeData, 'id');
    }

    /**
     * @return int
     */
    public function count株式会社(): int
    {
        return $this->kabushikigaishaNum;
    }

    /**
     * @return int
     */
    public function count有限会社(): int
    {
        return $this->yuugengaishaNum;
    }

    /**
     * @return int|null
     */
    public function countStatus(int $status): ?int
    {
        return $this->statusNum[$status] ?? null;
    }

    /**
     * @return void
     */
    private function prepareData(): void
    {
        for ($i = 0; $i < $this->numberOfRows; $i++) {
            // id
            $id = $i + 1;

            // name
            $name = $this->factory->company;
            if (Str::contains($name, '株式会社')) {
                $this->kabushikigaishaNum++;
            }
            else if (Str::contains($name, '有限会社')) {
                $this->yuugengaishaNum++;
            }

            // status
            $status = $this->factory->randomElement(self::$vendorStatusValues);
            if (!isset($this->statusNum[$status])) {
                $this->statusNum[$status] = 0;
            }
            $this->statusNum[$status]++;

            $this->fakeData[] = [
                'id' => $id,
                'name' => $name,
                'status' => $status,
            ];
        }
    }
}
