<?php

namespace DoSystemMock\Infrastructure\Repository;

use PHP_INT_MAX;
use Illuminate\Support\Arr;
use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Brand\Model\BrandValueName;
use DoSystem\Domain\Brand\Model\BrandValueOrder;
use DoSystem\Domain\Brand\Model\BrandValueStatus;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Exception\NotFoundException;
use PseudoDatabase\Database;

class InMemoryBrandRepository implements BrandRepositoryInterface
{
    /**
     * Brands table definition
     */
    private $definitions = [
        'id'        => ['primary' => true],
        'vendor_id' => [],
        'name'      => [],
        'status'    => [],
        'order'     => ['nullable' => true],
    ];

    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(VendorRepositoryInterface $vendorRepository)
    {
        $this->vendorRepository = $vendorRepository;

        if (Database::exists('brands')) {
            return;
        }
        Database::create('brands', $this->definitions);
    }

    /**
     * @param Brand $model
     * @return BrandValueId
     */
    public function store(Brand $model): BrandValueId
    {
        $maybeId = $model->getId();
        $data = [
            'vendor_id' => $model->belongsTo()->getId()->getValue(),
            'name'      => $model->getName()->getValue(),
            'status'    => $model->getStatus()->getValue(),
            'order'     => $model->getOrder()->getValue(),
        ];
        if ($maybeId->isPseudo()) {
            $id = Database::table('brands')->insert($data)['id'];
        }
        else {
            $id = $maybeId->getValue();
            Database::table('brands')->where('id', '=', $id)->update($data);
        }
        return BrandValueId::of($id);
    }

    /**
     * @param BrandValueId $id
     * @return Brand
     * @throws NotFoundException
     */
    public function findById(BrandValueId $id): Brand
    {
        $data = Database::table('brands')->where('id', '=', $id->getValue())->first();
        if ($data === null) {
            throw new NotFoundException('Not found');
        }
        return new Brand(
            BrandValueId::of($data['id']),
            $this->vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
            BrandValueName::of($data['name']),
            BrandValueStatus::of($data['status']),
            BrandValueOrder::of($data['order'])
        );
    }

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by
     *      @type string|null $order
     * } $params
     * @return BrandCollection
     */
    public function query(array $params): BrandCollection
    {
        $table = Database::table('brands');

        if (!empty($params)) {
            if ($vendorIds = Arr::pull($params, 'vendor_id')) {
                $table->where('vendor_id', 'IN', $vendorIds);
            }
            if ($name = Arr::pull($params, 'name')) {
                $table->where('name', 'LIKE', $name);
            }
            if ($statuses = Arr::pull($params, 'status')) {
                $table->where('status', 'IN', $statuses);
            }
            if ($size = Arr::pull($params, 'size_per_page')) {
                $page = Arr::pull($params, 'page', 1);
                $start = ($page - 1) * $size;
                $table->offset($start)->limit($size);
            }
        }

        $results = $table->get();

        if (empty($results)) {
            return new BrandCollection($results);
        }

        if ($orderBy = Arr::pull($params, 'order_by')) {
            if (\in_array($orderBy, ['name', 'status', 'order'], true)) {
                $results = Arr::sort($results, function ($row) use ($orderBy) {
                    return $row[$orderBy] === null ? PHP_INT_MAX : $row[$orderBy];
                });
                $order = \strtolower(Arr::pull($params, 'order', 'asc'));
                if ($order === 'desc') {
                    $results = \array_reverse($results);
                }
            }
        }

        $results = \array_map(function ($row) {
            return new Brand(
                BrandValueId::of($row['id']),
                $this->vendorRepository->findById(VendorValueId::of($row['vendor_id'])),
                BrandValueName::of($row['name']),
                BrandValueStatus::of($row['status']),
                BrandValueOrder::of($row['order'])
            );
        }, $results, []); // 2nd empty array for reassign keys

        return new BrandCollection($results);
    }

    /**
     * Delete all stored data
     *
     * @return void
     */
    public function flush(): void
    {
        Database::table('brands')->refresh();
    }
}
