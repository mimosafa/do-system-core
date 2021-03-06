<?php

namespace DoSystemCoreMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use DoSystem\Core\Domain\Vendor\Vendor;
use DoSystem\Core\Domain\Vendor\VendorCollection;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Domain\Vendor\VendorValueName;
use DoSystem\Core\Domain\Vendor\VendorValueStatus;
use DoSystem\Core\Exception\NotFoundException;
use PseudoDatabase\Database;

class InMemoryVendorRepository implements VendorRepositoryInterface
{
    /**
     * Vendors table definition
     */
    private $definitions = [
        'id'     => ['primary' => true],
        'name'   => ['type' => 'string'],
        'status' => ['type' => 'integer'],
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        if (Database::exists('vendors')) {
            return;
        }
        Database::create('vendors', $this->definitions);
    }

    /**
     * @param Vendor $model
     * @return VendorValueId
     */
    public function store(Vendor $model): VendorValueId
    {
        $maybeId = $model->getId();
        $data = [
            'name' => $model->getName()->getValue(),
            'status' => $model->getStatus()->getValue(),
        ];
        if ($maybeId->isPseudo()) {
            $id = Database::table('vendors')->insert($data)['id'];
        }
        else {
            $id = $maybeId->getValue();
            Database::table('vendors')->where('id', '=', $id)->update($data);
        }
        return VendorValueId::of($id);
    }

    /**
     * @param VendorValueId $id
     * @return Vendor
     * @throws NotFoundException
     */
    public function findById(VendorValueId $id): Vendor
    {
        $data = Database::table('vendors')->where('id', '=', $id->getValue())->first();
        if ($data === null) {
            throw new NotFoundException('Not found');
        }
        return new Vendor(
        VendorValueId::of($data['id']),
            VendorValueName::of($data['name']),
            VendorValueStatus::of($data['status'])
        );
    }

    /**
     * @param array{
     *      @type string|null $name
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by
     *      @type string|null $order
     * } $params
     * @return VendorCollection
     */
    public function query(array $params): VendorCollection
    {
        $table = Database::table('vendors');

        if (!empty($params)) {
            if ($name = Arr::pull($params, 'name')) {
                $table->where('name', 'LIKE', $name);
            }
            if ($status = Arr::pull($params, 'status')) {
                $table->where('status', 'IN', $status);
            }
            if ($size = Arr::pull($params, 'size_per_page')) {
                $page = Arr::pull($params, 'page', 1);
                $start = ($page - 1) * $size;
                $table->offset($start)->limit($size);
            }
            if ($orderBy = Arr::pull($params, 'order_by')) {
                if (\in_array($orderBy, ['name', 'status'], true)) {
                    $order = Arr::pull($params, 'order');
                    $table->orderBy($orderBy, $order);
                }
            }
        }

        $results = $table->get();

        if (empty($results)) {
            return new VendorCollection($results);
        }

        $results = \array_map(function ($row) {
            $id = VendorValueId::of($row['id']);
            $name = VendorValueName::of($row['name']);
            $status = VendorValueStatus::of($row['status']);
            return new Vendor($id, $name, $status);
        }, $results, []); // 2nd empty array for reassign keys

        return new VendorCollection($results);
    }

    /**
     * Delete all stored data
     *
     * @return void
     */
    public function flush(): void
    {
        Database::table('vendors')->refresh();
    }
}
