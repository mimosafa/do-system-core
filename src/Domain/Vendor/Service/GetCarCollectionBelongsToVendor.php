<?php

namespace DoSystem\Domain\Vendor\Service;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Service\FindCarCollectionBelongsToVendor;
use DoSystem\Domain\Vendor\Model\Vendor;

class GetCarCollectionBelongsToVendor
{
    /**
     * @var FindCarCollectionBelongsToVendor
     */
    private $service;

    /**
     * Constructor
     *
     * @param FindCarCollectionBelongsToVendor $service
     */
    public function __construct(FindCarCollectionBelongsToVendor $service)
    {
        $this->service = $service;
    }

    /**
     * @param Vendor $vendor
     * @param array $params
     * @return CarCollection
     */
    public function handle(Vendor $vendor, array $params = []): CarCollection
    {
        return $this->service->handle($vendor, $params);
    }
}
