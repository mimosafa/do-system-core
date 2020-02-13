<?php

namespace DoSystem\Domain\Car\Service;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Service\FindCarCollection;
use DoSystem\Domain\Vendor\Model\Vendor;

class FindCarCollectionBelongsToVendor
{
    /**
     * @var FindCarCollection
     */
    private $service;

    /**
     * Constructor
     *
     * @param FindCarCollection $service
     */
    public function __construct(FindCarCollection $service)
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
        $params['vendor'] = $vendor;
        return $this->service->handle($params);
    }
}
