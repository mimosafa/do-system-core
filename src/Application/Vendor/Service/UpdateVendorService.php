<?php

namespace DoSystem\Application\Vendor\Service;

use DoSystem\Application\Vendor\Data\UpdateVendorInputInterface;
use DoSystem\Application\Vendor\Data\UpdateVendorOutputInterface;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;

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
