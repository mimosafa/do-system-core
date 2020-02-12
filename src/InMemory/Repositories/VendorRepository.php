<?php

namespace DoSystem\InMemory\Repositories;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Exception\NotFoundException;

class VendorRepository implements VendorRepositoryInterface
{
    /**
     * @var array
     */
    private $db = [];

    /**
     * @param Vendor $model
     * @return VendorValueId
     * @throws NotFoundException
     */
    public function store(Vendor $model): VendorValueId
    {
        /** @var VendorValueId */
        $id = $model->getId();

        /** @var VendorValueName */
        $name = $model->getName();

        /** @var VendorValueStatus */
        $status = $model->getStatus();

        if (!$id->isPersisted()) {
            $int = count($this->db) + 1;
            $this->db[$int] = [];
        } else {
            $int = $id->getValue();
            if (!isset($this->db[$int])) {
                throw new NotFoundException('Not Found');
            }
        }

        $row =& $this->db[$int];

        $row['name'] = $name->getValue();
        $row['status'] = $status->getValue();

        return VendorValueId::of($int);
    }

    /**
     * @param VendorValueId $id
     * @return Vendor|null
     * @throws NotFoundException
     */
    public function findById(VendorValueId $id): Vendor
    {
        /** @var int */
        $int = $id->getValue();

        if (!isset($this->db[$int])) {
            throw new NotFoundException('Not found');
        }

        $row = $this->db[$int];

        return new Vendor(
            VendorValueId::of($int),
            VendorValueName::of($row['name']),
            VendorValueStatus::of($row['status'])
        );
    }
}
