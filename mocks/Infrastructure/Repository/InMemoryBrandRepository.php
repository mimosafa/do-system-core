<?php

namespace DoSystemMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Brand\BrandValueName;
use DoSystem\Core\Domain\Brand\BrandValueOrder;
use DoSystem\Core\Domain\Brand\BrandValueStatus;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Exception\NotFoundException;
use PseudoDatabase\Database;

class InMemoryBrandRepository implements BrandRepositoryInterface
{
    /**
     * Brands table definition
     */
    private $definitions = [
        'id'        => ['primary' => true],
        'vendor_id' => ['type' => 'integer'],
        'name'      => ['type' => 'string'],
        'status'    => ['type' => 'integer'],
        'order'     => ['nullable' => true, 'type' => 'integer'],
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
            if ($orderBy = Arr::pull($params, 'order_by')) {
                if (\in_array($orderBy, ['name', 'status', 'order'], true)) {
                    $order = Arr::pull($params, 'order');
                    $table->orderBy($orderBy, $order);
                    if ($orderBy === 'order') {
                        // Nullable key
                        $table->isNull('asc');
                    }
                }
            }
        }

        $results = $table->get();

        if (empty($results)) {
            return new BrandCollection($results);
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
