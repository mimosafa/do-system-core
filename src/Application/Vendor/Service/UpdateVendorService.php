<?php

namespace DoSystem\Core\Application\Vendor\Service;

use DoSystem\Core\Application\Vendor\Data\UpdateVendorInputInterface;
use DoSystem\Core\Application\Vendor\Data\UpdateVendorOutputInterface;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;

class UpdateVendorService
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
     * @param UpdateVendorInputInterface $input
     * @return UpdateVendorOutputInterface
     */
    public function handle(UpdateVendorInputInterface $input): UpdateVendorOutputInterface
    {
        $id = VendorValueId::of($input->getId());
        $model = $this->repository->findById($id);

        $name = $input->getName();

        /** @var array */
        $modified = [];

        if ($name !== null) {
            /** @var bool */
            $done = $model->setName(VendorValueName::of($name));
            if ($done) {
                $modified[] = VendorValueName::class;
            }
        }

        if (!empty($modified)) {
            $id = $this->repository->store($model);
        }

        return doSystem()->makeWith(UpdateVendorOutputInterface::class, [
            'model' => $this->repository->findById($id),
            'modified' => $modified,
        ]);
    }
}
