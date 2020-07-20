<?php

namespace DoSystem\Core\Application\Vendor\Service;

use DoSystem\Core\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;

class GetVendorService
{
    /**
     * @var VendorRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $repository
     */
    public function __construct(VendorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @uses doSystem()
     *
     * @param VendorValueId $id
     * @return GetVendorOutputInterface
     * @throws \DoSystem\Core\Exception\NotFoundException
     */
    public function handle(VendorValueId $id): GetVendorOutputInterface
    {
        $model = $this->repository->findById($id);
        return doSystem()->makeWith(GetVendorOutputInterface::class, ['model' => $model]);
    }
}
