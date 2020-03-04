<?php

namespace DoSystemMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandCollection;
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
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     * } $params
     * @return BrandCollection
     */
    public function query(array $params): BrandCollection
    {
        $result = $this->db;

        if (!empty($result)) {
            $vendorFilter = Arr::pull($params, 'vendor_id');
            $nameFilter = Arr::pull($params, 'name');
            $statusFilter = Arr::pull($params, 'status');

            $result = Arr::where($result, function ($row) use ($vendorFilter, $nameFilter, $statusFilter) {
                if ($vendorFilter && !\in_array($row['vendor_id'], $vendorFilter, true)) {
                    return false;
                }
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
            $page = Arr::pull($params, 'page', 1);
            $start = ($page - 1) * $size;
            $result = \array_slice($result, $start, $size);
        }

        if (!empty($result)) {
            $result = \array_map(function ($row) {
                return new Brand(
                    BrandValueId::of($row['id']),
                    $this->vendorRepository->findById(VendorValueId::of($row['vendor_id'])),
                    BrandValueName::of($row['name']),
                    BrandValueStatus::of($row['status'])
                );
            }, $result);
        }

        return new BrandCollection($result);
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
