<?php

namespace DoSystem\Application\Vendor\Service;

use DoSystem\Application\Vendor\Data\GetVendorOutputInterface;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;

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
     * @param int $id
     * @return GetVendorOutputInterface
     * @throws \DoSystem\Exception\NotFoundException
     */
    public function handle(int $id): GetVendorOutputInterface
    {
        $model = $this->repository->findById(VendorValueId::of($id));
        return doSystem()->makeWith(GetVendorOutputInterface::class, ['model' => $model]);
    }
}
