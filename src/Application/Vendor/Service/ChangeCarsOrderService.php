<?php

namespace DoSystem\Application\Vendor\Service;

use DoSystem\Application\Vendor\Data\ChangeCarsOrderInputInterface;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;

class ChangeCarsOrderService
{
    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     *
     */
    public function handle(ChangeCarsOrderInputInterface $input)
    {
        $vendorId = VendorValueId::of($input->getVendorId());
        /** @var int[] */
        $carIdsOrderList = $input->getCarIdsOrderList();

        //
    }
}
