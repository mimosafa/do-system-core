<?php

namespace DoSystemTest\Application\Brand\Service;

use Faker\Provider\Base as Faker;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Brand\Data;
use DoSystem\Application\Brand\Service;
use DoSystem\Domain\Brand\Model;
use DoSystemMock\Application\Brand\Data as MockData;
use DoSystemMock\Factory\BrandDataFactory;
use DoSystemMock\Infrastructure\Repository;
use DoSystemMock\Infrastructure\Seeder;

class BrandServiceTest extends TestCase
{
    /**
     * @var Repository\BrandRepositoryMock
     */
    private $brandRepository;

    /**
     * @var Repository\VendorRepositoryMock
     */
    private $vendorRepository;

    protected function setUp(): void
    {
        $this->vendorRepository ?? $this->vendorRepository = new Repository\VendorRepositoryMock();
        $this->brandRepository ?? $this->brandRepository = new Repository\BrandRepositoryMock($this->vendorRepository);
    }

    protected function tearDown(): void
    {
        $this->brandRepository->flush();
        $this->vendorRepository->flush();
    }

    /**
     * @test
     */
    public function testCreateBrand()
    {
        $createService = new Service\CreateBrandService($this->brandRepository, $this->vendorRepository);

        $vendorsSeeder = new Seeder\VendorsSeeder(1);
        $vendorsSeeder->seed($this->vendorRepository);
        $vendorsData = $vendorsSeeder->getData();
        $vendorId = $vendorsData[0]['id'];

        $data = BrandDataFactory::generate($vendorId);
        $input = new MockData\CreateBrandInputMock();
        $input->vendorId = $data['vendor_id'];
        $input->name = $data['name'];

        $id = $createService->handle($input);

        $this->assertTrue($id instanceof Model\BrandValueId);

        $model = $this->brandRepository->findById($id);

        $this->assertEquals($model->belongsTo()->getId()->getValue(), $data['vendor_id']);
        $this->assertEquals($model->getName()->getValue(), $data['name']);
        $this->assertEquals($model->getStatus()->getValue(), Model\BrandValueStatus::default()->getValue());
    }

    /**
     * @test
     */
    public function testGetBrand()
    {
        $getService = new Service\GetBrandService($this->brandRepository);
        $seeder = new Seeder\BrandsSeeder(6, 2);
        $seeder->seed($this->brandRepository, $this->vendorRepository);
        $data = $seeder->getData();

        $id3 = Model\BrandValueId::of($data[2]['id']);

        $output3 = $getService->handle($id3);

        $this->assertTrue($output3 instanceof Data\GetBrandOutputInterface);

        $this->assertEquals($output3->name->getValue(), $data[2]['name']);
    }

    /**
     * @test
     */
    public function testUpdateBrand()
    {
        $updateService = new Service\UpdateBrandService($this->brandRepository);
        $seeder = new Seeder\BrandsSeeder(5, 2);
        $seeder->seed($this->brandRepository, $this->vendorRepository);
        $data = $seeder->getData();
        $ids = Faker::randomElements(\array_column($data, 'id'), 2);

        // Update name
        $idName = Model\BrandValueId::of($ids[0]);
        $modelName = $this->brandRepository->findById($idName);
        $nameBefore = $modelName->getName()->getValue();
        $nameAfter = 'Awesome Restaurant';

        $this->assertNotEquals($nameBefore, $nameAfter);

        $inputName = new MockData\UpdateBrandInputMock();
        $inputName->id = $idName->getValue();
        $inputName->name = $nameAfter;
        $outputName = $updateService->handle($inputName);

        $this->assertTrue($outputName instanceof Data\UpdateBrandOutputInterface);
        $this->assertEquals(count($outputName->modified), 1);
        $this->assertEquals($outputName->modified[0], Model\BrandValueName::class);
        $this->assertEquals($this->brandRepository->findById($idName)->getName()->getValue(), $nameAfter);
    }
}
