<?php

namespace DoSystemMock\Factory;

use Faker\Factory;
use Illuminate\Support\Str;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

class CarsFactory
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
     * @var array[]
     */
    private $fakeData = [];

    /**
     * Generated data counts for tests
     */
    private $counts =[
        'vendor_id' => [],
        'vin'       => ['品川' => 0, '足立' => 0, '練馬' => 0,],
        'status'    => [],
    ];

    /**
     * @var int[]
     */
    private $vendorIds;

    /**
     * Status int values
     *
     * @static
     * @var int[]
     */
    private static $carStatusIntValues;

    /**
     * Constructor
     *
     * @param int $numberOfRows
     * @param int[] $vendorIds
     */
    public function __construct(int $numberOfRows, array $vendorIds)
    {
        $this->faker = Factory::create('ja_JP');
        $this->numberOfRows = $numberOfRows;
        $this->vendorIds = $vendorIds;
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
     * @param int $id
     * @return int
     */
    public function countByVendorId(int $id): int
    {
        return $this->counts['vendor_id'][$id] ?? 0;
    }

    /**
     * @return int
     */
    public function countByVin品川(): int
    {
        return $this->counts['vin']['品川'];
    }
    public function countByVin足立(): int
    {
        return $this->counts['vin']['足立'];
    }
    public function countByVin練馬(): int
    {
        return $this->counts['vin']['練馬'];
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
        $vinPattern = ValueObjectVin::getRegexPattern();
        $vinPattern = \preg_replace('/^\^\(\W+\)/', '^(品川|足立|練馬)', $vinPattern);

        for ($i = 0; $i < $this->numberOfRows; $i++) {
            // vendor_id
            $vendorId = $this->faker->randomElement($this->vendorIds);
            if (!isset($this->counts['vendor_id'][$vendorId])) {
                $this->counts['vendor_id'][$vendorId] = 0;
            }
            $this->counts['vendor_id'][$vendorId]++;

            // vin
            $vin = $this->faker->regexify($vinPattern);
            if (Str::contains($vin, '品川')) {
                $this->counts['vin']['品川']++;
            }
            else if (Str::contains($vin, '足立')) {
                $this->counts['vin']['足立']++;
            }
            else if (Str::contains($vin, '練馬')) {
                $this->counts['vin']['練馬']++;
            }

            // status
            $status = $this->faker->randomElement(self::getCarStatusIntValues());
            if (!isset($this->counts['status'][$status])) {
                $this->counts['status'][$status] = 0;
            }
            $this->counts['status'][$status]++;

            // name
            $nameSafix = $this->faker->randomElement(['', '号', '車']);
            $name = $nameSafix ? $this->faker->lastName . $nameSafix : '';

            $this->fakeData[] = [
                'vendor_id' => $vendorId,
                'vin' => $vin,
                'status' => $status,
                'name' => $name,
            ];
        }

        // var_dump($this->fakeData);
    }

    /**
     * @return int[]
     */
    private static function getCarStatusIntValues(): array
    {
        if (self::$carStatusIntValues === null) {
            $valueObjects = CarValueStatus::values();
            self::$carStatusIntValues = \array_map(function ($o) {
                return $o->getValue();
            }, $valueObjects);
        }
        return self::$carStatusIntValues;
    }
}
