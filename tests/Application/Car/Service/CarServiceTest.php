<?php

namespace DoSystemTest\Application\Car\Service;

use Faker\Provider\Base as Faker;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Car\Data;
use DoSystem\Application\Car\Service;
use DoSystem\Domain\Car\Model;
use DoSystem\Domain\Car\Service as DomainService;
use DoSystemMock\Application\Car\Data as MockData;
use DoSystemMock\Factory\CarDataFactory;
use DoSystemMock\Infrastructure\Repository;
use DoSystemMock\Infrastructure\Seeder;

class CarServiceTest extends TestCase
{
    /**
     * @var Repository\CarRepositoryMock
     */
    private $carRepository;

    /**
     * @var Repository\VendorRepositoryMock
     */
    private $vendorRepository;

    protected function setUp(): void
    {
        $this->vendorRepository ?? $this->vendorRepository = new Repository\VendorRepositoryMock();
        $this->carRepository ?? $this->carRepository = new Repository\CarRepositoryMock($this->vendorRepository);
    }

    protected function tearDown(): void
    {
        $this->carRepository->flush();
        $this->vendorRepository->flush();
    }

    /**
     * @test
     */
    public function testCreateCar()
    {
        $domainCarService = new DomainService\CarService($this->carRepository);
        $createService = new Service\CreateCarService(
            $this->carRepository, $this->vendorRepository, $domainCarService
        );

        $vendorsSeeder = new Seeder\VendorsSeeder(1);
        $vendorsSeeder->seed($this->vendorRepository);
        $vendorsData = $vendorsSeeder->getData();
        $vendorId = $vendorsData[0]['id'];

        $data = CarDataFactory::generate($vendorId);
        $input = new MockData\CreateCarInputMock();
        $input->vendorId = $data['vendor_id'];
        $input->vin = $data['vin'];
        $input->name = $data['name'];

        $id = $createService->handle($input);

        $this->assertTrue($id instanceof Model\CarValueId);

        $model = $this->carRepository->findById($id);

        $this->assertEquals($model->belongsTo()->getId()->getValue(), $data['vendor_id']);
        $this->assertEquals($model->getVin()->getValue(), $data['vin']);
        $this->assertEquals($model->getStatus()->getValue(), Model\CarValueStatus::default()->getValue());
        $this->assertEquals($model->getName()->getValue(), $data['name']);
    }

    /**
     * @test
     */
    public function testGetCar()
    {
        $getService = new Service\GetCarService($this->carRepository);
        $seeder = new Seeder\CarsSeeder(7, 3);
        $seeder->seed($this->carRepository, $this->vendorRepository);
        $data = $seeder->getData();

        $id2 = Model\CarValueId::of($data[1]['id']);
        $id6 = Model\CarValueId::of($data[5]['id']);

        $output2 = $getService->handle($id2);
        $output6 = $getService->handle($id6);

        $this->assertTrue($output2 instanceof Data\GetCarOutputInterface);

        $this->assertEquals($output2->getVin()->getValue(), $data[1]['vin']);
        $this->assertEquals($output6->getVin()->getValue(), $data[5]['vin']);
    }

    /**
     * @test
     */
    public function testUpdate()
    {
        $domainCarService = new DomainService\CarService($this->carRepository);
        $updateService = new Service\UpdateCarService($this->carRepository, $domainCarService);
        $seeder = new Seeder\CarsSeeder(5, 2);
        $seeder->seed($this->carRepository, $this->vendorRepository);
        $data = $seeder->getData();
        $ids = Faker::randomElements(\array_column($data, 'id'), 3);

        // Update vin
        $idVin = Model\CarValueId::of($ids[0]);
        $modelVin = $this->carRepository->findById($idVin);
        $vinBefore = $modelVin->getVin()->getValue();
        $vinAfter = Faker::regexify(Model\CarValueVin::getRegexPattern());

        $this->assertNotEquals($vinBefore, $vinAfter);

        $inputVin = new MockData\UpdateCarInputMock();
        $inputVin->id = $idVin->getValue();
        $inputVin->vin = $vinAfter;
        $outputVin = $updateService->handle($inputVin);

        $this->assertTrue($outputVin instanceof Data\UpdateCarOutputInterface);
        $this->assertEquals(count($outputVin->modified), 1);
        $this->assertEquals($outputVin->modified[0], Model\CarValueVin::class);
        $this->assertEquals($this->carRepository->findById($idVin)->getVin()->getValue(), $vinAfter);

        // Update name
        $idName = Model\CarValueId::of($ids[1]);
        $modelName = $this->carRepository->findById($idName);
        $nameBefore = $modelName->getName()->getValue();
        $nameAfter = 'Awesome Car Name';

        $this->assertNotEquals($nameBefore, $nameAfter);

        $inputName = new MockData\UpdateCarInputMock();
        $inputName->id = $idName->getValue();
        $inputName->name = $nameAfter;
        $outputName = $updateService->handle($inputName);

        $this->assertEquals(count($outputName->modified), 1);
        $this->assertEquals($outputName->modified[0], Model\CarValueName::class);
        $this->assertEquals($this->carRepository->findById($idName)->getName()->getValue(), $nameAfter);

        // Update vin & name
        $idVinName = Model\CarValueId::of($ids[2]);
        $modelVinName = $this->carRepository->findById($idVinName);
        $vinBefore2 = $modelVinName->getVin()->getValue();
        $nameBefore2 = $modelVinName->getName()->getValue();
        $vinAfter2 = Faker::regexify(Model\CarValueVin::getRegexPattern());
        $nameAfter2 = '素晴らしい車';

        $this->assertNotEquals($vinBefore2, $vinAfter2);
        $this->assertNotEquals($nameBefore2, $nameAfter2);

        $inputVinName = new MockData\UpdateCarInputMock();
        $inputVinName->id = $idVinName->getValue();
        $inputVinName->vin = $vinAfter2;
        $inputVinName->name = $nameAfter2;
        $outputVinName = $updateService->handle($inputVinName);

        $this->assertEquals(count($outputVinName->modified), 2);
        $modelVinName2 = $this->carRepository->findById($idVinName);
        $this->assertEquals($modelVinName2->getVin()->getValue(), $vinAfter2);
        $this->assertEquals($modelVinName2->getName()->getValue(), $nameAfter2);
    }

    /**
     * @test
     */
    public function testQueryCar()
    {
        $queryService = new Service\QueryCarService($this->carRepository);
        $seeder = new Seeder\CarsSeeder(30, 15);
        $seeder->seed($this->carRepository, $this->vendorRepository);
        $data = $seeder->getData();

        $filterAll = new MockData\QueryCarFilterMock();
        $resultAll = $queryService->handle($filterAll);

        $this->assertEquals(count($resultAll), 30);
        $this->assertTrue($resultAll[0] instanceof Data\QueriedCarOutputInterface);

        $filterVendorId = new MockData\QueryCarFilterMock();
        $filterVendorId->vendorId = [1, 5, 14];
        $resultVendorId = $queryService->handle($filterVendorId);

        $filterVin = new MockData\QueryCarFilterMock();
        $filterVin->vin = '9';
        $resultVin = $queryService->handle($filterVin);

        $filterStatus = new MockData\QueryCarFilterMock();
        $filterStatus->status = [4, 6, 7];
        $resultStatus = $queryService->handle($filterStatus);

        $vendorId1514Num = 0;
        $vinContains9Num = 0;
        $status467Num = 0;
        foreach ($data as $arr) {
            if (\in_array($arr['vendor_id'], [1, 5, 14], true)) {
                $vendorId1514Num++;
            }
            if (Str::contains($arr['vin'], '9')) {
                $vinContains9Num++;
            }
            if (\in_array($arr['status'], [4, 6, 7], true)) {
                $status467Num++;
            }
        }
        $this->assertEquals(count($resultVendorId), $vendorId1514Num);
        $this->assertEquals(count($resultVin), $vinContains9Num);
        $this->assertEquals(count($resultStatus), $status467Num);

        $filterPage = new MockData\QueryCarFilterMock();
        $filterPage->sizePerPage = 7;
        $filterPage->page = 5;
        $resultPage = $queryService->handle($filterPage);

        $this->assertEquals(count($resultPage), 30 - (7 * (5 - 1)));
        $this->assertEquals($resultPage[0]->getVin()->getValue(), $data[28]['vin']);
    }
}
