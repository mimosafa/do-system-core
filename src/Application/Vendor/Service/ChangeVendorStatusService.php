<?php

namespace DoSystem\Core\Application\Vendor\Service;

class ChangeVendorStatusService
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
     *
     */
    public function handle(UpdateVendorInputInterface $input): UpdateVendorOutputInterface
    {
        //
    }
}
