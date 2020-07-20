<?php

namespace DoSystem\Core\Application\Car\Service;

use DoSystem\Core\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Core\Domain\Car\Car;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Car\CarValueName;
use DoSystem\Core\Domain\Car\CarValueOrder;
use DoSystem\Core\Domain\Car\CarValueStatus;
use DoSystem\Core\Domain\Car\CarValueVin;
use DoSystem\Domain\Car\Service\CarService;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;

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
