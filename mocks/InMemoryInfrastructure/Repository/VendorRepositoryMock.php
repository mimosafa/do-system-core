<?php

namespace DoSystemMock\InMemoryInfrastructure\Repository;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorCollection;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorValueName;
use DoSystem\Domain\Vendor\Model\VendorValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Exception\NotFoundException;

class VendorRepositoryMock implements VendorRepositoryInterface
{
    /**
     * @var array
     */
    private $db = [
        // ['id' => 1,  'name' => 'Test Vendor', 'status' => 4],
        // ['id' => 2,  'name' => 'McDonald',    'status' => 3],
        // ['id' => 3,  'name' => 'Mos Buarger', 'status' => 3],
    ];

    /**
     * @var int
     */
    private $lastId = 0;

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

        if ($id->exists()) {
            $int = $id->getValue();
            $ids = array_column($this->db, 'id');
            if (!$i = array_search($int, $ids, true)) {
                throw new NotFoundException('Not Found');
            }
            $row =& $this->db[$i];
        }
        else {
            $int = ++$this->lastId;
            $this->db[] = ['id' => $int];
            $row =& $this->db[count($this->db) - 1];
        }

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

        $ids = array_column($this->db, 'id');
        $row = $this->db[array_search($int, $ids, true)] ?? null;

        if (!isset($row)) {
            throw new NotFoundException('Not found');
        }

        return new Vendor(
            VendorValueId::of($int),
            VendorValueName::of($row['name']),
            VendorValueStatus::of($row['status'])
        );
    }

    /**
     * @param array $params
     * @return VendorCollection
     */
    public function query(array $params): VendorCollection
    {
        $result = $this->db;

        if (!empty($params)) {
            $nameFilter = Arr::pull($params, 'name');
            $statusFilter = Arr::pull($params, 'status');

            $result = Arr::where($result, function ($row) use ($nameFilter, $statusFilter) {
                if ($nameFilter && !Str::contains($row['name'], $nameFilter)) {
                    return false;
                }
                if ($statusFilter && !\in_array($row['status'], $statusFilter, true)) {
                    return false;
                }
                return true;
            });
        }

        if (!empty($result) && $size = Arr::pull($params, 'size_per_page')) {
            $page = Arr::pull($params, 'page');
            $start = ($page - 1) * $size;
            $result = \array_slice($result, $start, $size);
        }

        if (!empty($result)) {
            $size = Arr::pull($params, 'size_per_page');
            $result = \array_map(function ($row) {
                $id = VendorValueId::of($row['id']);
                $name = VendorValueName::of($row['name']);
                $status = VendorValueStatus::of($row['status']);
                return new Vendor($id, $name, $status);
            }, $result);
        }

        return new VendorCollection($result);
    }

    /**
     * Flush $db & $lastId for testing
     */
    public function flush()
    {
        $this->db = [];
        $this->lastId = 0;
    }
}
