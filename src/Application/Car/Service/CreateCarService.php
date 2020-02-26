<?php

namespace DoSystem\Application\Car\Service;

use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

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
     * Constructor
     *
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(CarRepositoryInterface $carRepository, VendorRepositoryInterface $vendorRepository)
    {
        $this->carRepository = $carRepository;
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param CreateCarInputInterface $input
     * @return CarValueId
     */
    public function handle(CreateCarInputInterface $input): CarValueId
    {
        $id = CarValueId::of(null); // Pseudo Id for createing

        $vendorId = $input->getVendorId();
        $vendor = $this->vendorRepository->findById(VendorValueId::of($vendorId));
        $vin = ValueObjectVin::of($input->getVin());

        $status = $input->getStatus();

        // If not set $status, pass default status
        $status = isset($status) ? CarValueStatus::of($status) : CarValueStatus::default();

        $name = CarValueName::of($input->getName());

        return $this->carRepository->store(new Car($id, $vendor, $vin, $status, $name));
    }
}
