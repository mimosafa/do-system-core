<?php

namespace DoSystemTest\Application\Vendor\Service;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use DoSystem\Application\Vendor\Data;
use DoSystem\Application\Vendor\Service;
use DoSystem\Domain\Vendor\Model;
use DoSystemMock\Application\Vendor\Data as MockData;
use DoSystemMock\Factory\VendorDataFactory;
use DoSystemMock\Infrastructure\Repository\VendorRepositoryMock;
use DoSystemMock\Infrastructure\Seeder\VendorsSeeder;

class VendorServiceTest extends TestCase
{
    /**
     * @var VendorRepositoryMock
     */
    private $repository;

    protected function setUp(): void
    {
        $this->repository ?? $this->repository = new VendorRepositoryMock();
    }

    protected function tearDown(): void
    {
        $this->repository->flush();
    }

    /**
     * @test
     */
    public function testCreateVendor()
    {
        $createService = new Service\CreateVendorService($this->repository);
        $data = VendorDataFactory::generate();
        $input = new MockData\CreateVendorInputMock();
        $input->name = $data['name'];
        $id = $createService->handle($input);

        $this->assertTrue($id instanceof Model\VendorValueId);

        $model = $this->repository->findById($id);

        $this->assertEquals($model->getName()->getValue(), $data['name']);
        $this->assertEquals($model->getStatus()->getValue(), Model\VendorValueStatus::default()->getValue());
    }

    /**
     * @test
     */
    public function testGetVendor()
    {
        $getService = new Service\GetVendorService($this->repository);
        $seeder = new VendorsSeeder(5);
        $seeder->seed($this->repository);
        $data = $seeder->getData();

        $id1 = Model\VendorValueId::of($data[0]['id']);
        $id5 = Model\VendorValueId::of($data[4]['id']);

        $output1 = $getService->handle($id1);
        $output5 = $getService->handle($id5);

        $this->assertTrue($output1 instanceof Data\GetVendorOutputInterface);

        $this->assertEquals($output1->getName()->getValue(), $data[0]['name']);
        $this->assertEquals($output1->getStatus()->getValue(), $data[0]['status']);
        $this->assertEquals($output5->getName()->getValue(), $data[4]['name']);
        $this->assertEquals($output5->getStatus()->getValue(), $data[4]['status']);
    }

    /**
     * @test
     */
    public function testUpdateVendor()
    {
        $createService = new Service\CreateVendorService($this->repository);
        $updateService = new Service\UpdateVendorService($this->repository);
        $data = VendorDataFactory::generate();
        $createInput = new MockData\CreateVendorInputMock();
        $createInput->name = $data['name'];
        $id = $createService->handle($createInput);
        $model = $this->repository->findById($id);
        $newName = 'New Vendor Name';

        $this->assertNotEquals($model->getName()->getValue(), $newName);

        $updateInput = new MockData\UpdateVendorInputMock();
        $updateInput->id = $id->getValue();
        $updateInput->name = $newName;
        $updateOutput = $updateService->handle($updateInput);

        $this->assertTrue($updateOutput->model->getId()->equals($id));
        $this->assertEquals(count($updateOutput->modified), 1);
        $this->assertEquals($updateOutput->modified[0], Model\VendorValueName::class);
        $this->assertEquals($updateOutput->model->getName()->getValue(), $newName);
    }

    /**
     * @test
     */
    public function testQueryVendor()
    {
        $queryService = new Service\QueryVendorService($this->repository);
        $seeder = new VendorsSeeder(20);
        $seeder->seed($this->repository);
        $data = $seeder->getData();

        $filterAll = new MockData\QueryVendorFilterMock();
        $resultAll = $queryService->handle($filterAll);

        $this->assertEquals(count($resultAll), 20);
        $this->assertTrue($resultAll[0] instanceof Data\QueriedVendorOutputInterface);

        $filterName = new MockData\QueryVendorFilterMock();
        $filterName->name = '株式会社';
        $resultName = $queryService->handle($filterName);

        $filterStatus = new MockData\QueryVendorFilterMock();
        $filterStatus->status = [0, 3, 7,];
        $resultStatus = $queryService->handle($filterStatus);

        $kabushikigaishaNum = 0;
        $status037Num = 0;
        foreach ($data as $arr) {
            if (Str::contains($arr['name'], '株式会社')) {
                $kabushikigaishaNum++;
            }
            if (\in_array($arr['status'], [0, 3, 7,], true)) {
                $status037Num++;
            }
        }
        $this->assertEquals(count($resultName), $kabushikigaishaNum);
        $this->assertEquals(count($resultStatus), $status037Num);

        $filterOrder = new MockData\QueryVendorFilterMock();
        $filterOrder->orderBy = 'status';
        $filterOrder->order = 'DESC';
        $resultOrder = $queryService->handle($filterOrder);
        $statusCache = 9;
        $c = 0;
        foreach ($resultOrder as $output) {
            $status = $output->model->getStatus()->getValue();
            if ($status > $statusCache) {
                break;
            }
            $c++;
            if ($status < $statusCache) {
                $statusCache = $status;
            }
        }

        $this->assertEquals($c, 20);
    }
}
