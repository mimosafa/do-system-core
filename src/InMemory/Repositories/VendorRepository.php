<?php

namespace DoSystem\InMemory\Repositories;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
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
        /** @var VendorValueId|null */
        $id = $model->getId();

        /** @var VendorValueName */
        $name = $model->getName();

        if ($id === null) {
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

        return new VendorValueId($int);
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
            new VendorValueId($int),
            new VendorValueName($row['name'])
        );
    }
}