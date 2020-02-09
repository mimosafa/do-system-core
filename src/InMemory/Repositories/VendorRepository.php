<?php

namespace DoSystem\InMemory\Repositories;

use DoSystem\Domain\Models\Vendor\Vendor;
use DoSystem\Domain\Models\Vendor\VendorValueId;
use DoSystem\Domain\Models\Vendor\VendorValueName;
use DoSystem\Domain\Repositories\VendorRepositoryInterface;
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
            $int = count($this->db);
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
    public function find(VendorValueId $id): Vendor
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
