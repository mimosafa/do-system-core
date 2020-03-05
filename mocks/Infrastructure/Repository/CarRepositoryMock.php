<?php

namespace DoSystemMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueOrder;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Exception\NotFoundException;

class CarRepositoryMock implements CarRepositoryInterface
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
     * @param Car $model
     * @return CarValueId
     * @throws NotFoundException
     */
    public function store(Car $model): CarValueId
    {
        /** @var CarValueId */
        $maybeId = $model->getId();

        /** @var Vendor */
        $vendor = $model->belongsTo();

        /** @var CarValueVin */
        $vin = $model->getVin();

        /** @var CarValueStatus */
        $status = $model->getStatus();

        /** @var CarValueName */
        $name = $model->getName();

        $order = $model->getOrder();

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
        $row['vin'] = $vin->getValue();
        $row['status'] = $status->getValue();
        $row['name'] = $name->getValue();
        $row['order'] = $order->getValue();

        return CarValueId::of($id);
    }

    /**
     * @param CarValueId $id
     * @return Car
     * @throws NotFoundException
     */
    public function findById(CarValueId $id): Car
    {
        /** @var int */
        $int = $id->getValue();

        $ids = \array_column($this->db, 'id');
        $i = \array_search($int, $ids, true);
        if ($i !== false) {
            $row = $this->db[$i];
        }
        if (!isset($row)) {
            throw new NotFoundException('Not found');
        }

        $id = CarValueId::of($int);
        $vendor = $this->vendorRepository->findById(VendorValueId::of($row['vendor_id']));
        $vin = CarValueVin::of($row['vin']);
        $status = CarValueStatus::of($row['status']);
        $name = CarValueName::of($row['name']);
        $order = CarValueOrder::of($row['order']);

        return new Car($id, $vendor, $vin, $status, $name, $order);
    }

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $vin
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by  'id'|'status'|'order'
     *      @type string|null $order  'ASC'|'DESC'
     * } $params
     * @return CarCollection
     */
    public function query(array $params): CarCollection
    {
        $result = $this->db;

        if (!empty($result)) {
            $vendorFilter = Arr::pull($params, 'vendor_id');
            $vinFilter = Arr::pull($params, 'vin');
            $statusFilter = Arr::pull($params, 'status');

            $result = Arr::where($result, function ($row) use ($vendorFilter, $vinFilter, $statusFilter) {
                if ($vendorFilter && !\in_array($row['vendor_id'], $vendorFilter, true)) {
                    return false;
                }
                if ($vinFilter && !Str::contains($row['vin'], $vinFilter)) {
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

        if (empty($result)) {
            return new CarCollection($result);
        }

        if ($orderBy = Arr::pull($params, 'order_by')) {
            if (\in_array($orderBy, ['status', 'order'], true)) {
                $result = Arr::sort($result, function ($row) use ($orderBy) {
                    return $row[$orderBy];
                });
                $order = \strtolower(Arr::pull($params, 'order', 'asc'));
                if ($order === 'desc') {
                    $result = \array_reverse($result);
                }
            }
        }

        $result = \array_map(function ($row) {
            $id = CarValueId::of($row['id']);
            $vendor = $this->vendorRepository->findById(VendorValueId::of($row['vendor_id']));
            $vin = CarValueVin::of($row['vin']);
            $status = CarValueStatus::of($row['status']);
            $name = CarValueName::of($row['name']);
            $order = CarValueOrder::of($row['order']);
            return new Car($id, $vendor, $vin, $status, $name, $order);
        }, $result);

        return new CarCollection($result);
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
