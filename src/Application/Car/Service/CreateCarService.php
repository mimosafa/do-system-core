<?php

namespace DoSystem\Application\Car\Service;

use DoSystem\Application\Car\Data\CreateCarInputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
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
     * @param CreateCarInputInterface $data
     * @return CarValueId
     */
    public function handle(CreateCarInputInterface $data): CarValueId
    {
        // Pseudo Id for createing
        $id = CarValueId::of(null);

        if (!$vendorId = $data->getVendorId()) {
            // $vendorId is required
            throw new \Exception();
        }
        $vendor = $this->vendorRepository->findById(VendorValueId::of($vendorId));

        if (!$vin = $data->getVin()) {
            // $vin is required
            throw new \Exception();
        }
        $vin = CarValueVin::of($vin);

        $status = $data->getStatus();

        // If not set $status, pass default status
        $status = isset($status) ? CarValueStatus::of($status) : CarValueStatus::default();

        $name = CarValueName::of($data->getName());

        return $this->carRepository->store(new Car($id, $vendor, $vin, $status, $name));
    }
}
