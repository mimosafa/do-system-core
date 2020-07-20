<?php

namespace DoSystemTest\Domain\Car\Service;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Domain\Car\Service\VinService;
use DoSystemCoreMock\Infrastructure\Repository\InMemoryCarRepository;
use DoSystemCoreMock\Infrastructure\Repository\InMemoryVendorRepository;
use DoSystemCoreMock\Database\Seeder\CarsSeeder;
use DoSystemCoreMock\Database\Seeder\VendorsSeeder;

class VinServiceTest extends TestCase
{
    /**
     * @test
     */
    public function testExists()
    {
        $vendorRepository = new InMemoryVendorRepository();
        $carRepository = new InMemoryCarRepository($vendorRepository);

        $seeder = new CarsSeeder(1, (new VendorsSeeder(1))->seed($vendorRepository));
        $data = $seeder->seed($carRepository, $vendorRepository)->get();
        $existsVin = $data[0]['vin'];

        $vinService = new VinService($carRepository);

        $this->assertTrue($vinService->exists($existsVin));

        $vendorRepository->flush();
        $carRepository->flush();
    }

    /**
     * @test
     */
    public function testFormat()
    {
        $str1 = '多摩５００さ４６－４９';
        $str2 = 'なにわ　０００Ｙ・・・０';
        $str3 = '習志野 ３００ そ 90-34';
        $invalid1 = 'あ品川88 42-19';
        $invalid2 = '東京５００さ４６－４９';

        $this->assertEquals(VinService::format($str1), '多摩500さ4649');
        $this->assertEquals(VinService::format($str2), 'なにわ000Y0');
        $this->assertEquals(VinService::format($str3), '習志野300そ9034');
        $this->assertEquals(VinService::format($invalid1), '');
        $this->assertEquals(VinService::format($invalid2), '');
    }
}
