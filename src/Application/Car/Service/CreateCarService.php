<?php

namespace DoSystem\Application\Car\Service;

use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueOrder;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Car\Service\CarService;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;

class CreateCarService
{
    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * @var CarService
     */
    private $service;

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $carRepository
     * @param VendorRepositoryInterface $vendorRepository
     * @param CarService $service
     */
    public function __construct(CarRepositoryInterface $carRepository, VendorRepositoryInterface $vendorRepository, CarService $service)
    {
        $this->carRepository = $carRepository;
        $this->vendorRepository = $vendorRepository;
        $this->service = $service;
    }

    /**
     * @param CreateCarInputInterface $input
     * @return CarValueId
     */
    public function handle(CreateCarInputInterface $input): CarValueId
    {
        // Vendor
        $vendorId = $input->getVendorId();
        $vendor = $this->vendorRepository->findById(VendorValueId::of($vendorId));

        // Vin
        $vinString = $input->getVin();
        if ($this->service->vinExists($vinString)) {
            throw new \Exception();
        }
        $vin = CarValueVin::of($vinString);

        // Status, if not set, pass the default
        $statusInt = $input->getStatus();
        $status = isset($statusInt) ? CarValueStatus::of($statusInt) : CarValueStatus::default();

        // Name
        $name = CarValueName::of($input->getName());

        // Order
        $order = CarValueOrder::of($input->getOrder());

        // Pseudo Id for createing
        $id = CarValueId::of(null);

        return $this->carRepository->store(new Car($id, $vendor, $vin, $status, $name, $order));
    }
}
