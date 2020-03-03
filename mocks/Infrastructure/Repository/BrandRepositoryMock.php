<?php

namespace DoSystemMock\Infrastructure\Repository;

use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Brand\Model\BrandValueName;
use DoSystem\Domain\Brand\Model\BrandValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Exception\NotFoundException;

class BrandRepositoryMock implements BrandRepositoryInterface
{
    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * @var array[]
     */
    private $db = [];

    /**
     * @var int
     */
    private $lastId = 0;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;
    }

    /**
     * @param Brand $model
     * @return BrandValueId
     */
    public function store(Brand $model): BrandValueId
    {
        $maybeId = $model->getId();
        $vendor = $model->belongsTo();
        $name = $model->getName();
        $status = $model->getStatus();

        if ($maybeId->isPseudo()) {
            $id = ++$this->lastId;
            $this->db[] = ['id' => $id];
            $row =& $this->db[count($this->db) - 1];
        }
        else {
            $id = $maybeId->getValue();
            $ids = \array_column($this->db, 'id');
            $i = \array_search($id, $ids, true);
            if ($i === false) {
                throw new NotFoundException('Not Found');
            }
            $row =& $this->db[$i];
        }

        $row['vendor_id'] = $vendor->getId()->getValue();
        $row['name'] = $name->getValue();
        $row['status'] = $status->getValue();

        return BrandValueId::of($id);
    }

    /**
     * @param BrandValueId $id
     * @return Brand
     */
    public function findById(BrandValueId $id): Brand
    {
        $int = $id->getValue();
        $ids = \array_column($this->db, 'id');
        $i = \array_search($int, $ids, true);
        if ($i !== false) {
            $row = $this->db[$i];
        }
        if (!isset($row)) {
            throw new NotFoundException('Not found');
        }

        return new Brand(
            BrandValueId::of($int),
            $this->vendorRepository->findById(VendorValueId::of($row['vendor_id'])),
            BrandValueName::of($row['name']),
            BrandValueStatus::of($row['status'])
        );
    }

    /**
     * Flush $db & $lastId for tests
     */
    public function flush()
    {
        $this->db = [];
        $this->lastId = 0;
    }
}
