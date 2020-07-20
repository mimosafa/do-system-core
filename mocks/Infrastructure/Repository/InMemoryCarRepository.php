<?php

namespace DoSystemMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueOrder;
use DoSystem\Domain\Car\Model\CarValueStatus;
use DoSystem\Domain\Car\Model\CarValueVin;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Exception\NotFoundException;
use PseudoDatabase\Database;

class InMemoryCarRepository implements CarRepositoryInterface
{
    /**
     * Cars table definition
     */
    private $definitions = [
        'id'        => ['primary' => true],
        'vendor_id' => ['type' => 'integer'],
        'vin'       => ['unique' => true, 'type' => 'string'],
        'status'    => ['type' => 'integer'],
        'name'      => ['nullable' => true, 'type' => 'string'],
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

        if (Database::exists('cars')) {
            return;
        }
        Database::create('cars', $this->definitions);
    }

    /**
     * @param Car $model
     * @return CarValueId
     */
    public function store(Car $model): CarValueId
    {
        $maybeId = $model->getId();
        $data = [
            'vendor_id' => $model->belongsTo()->getId()->getValue(),
            'vin' => $model->getVin()->getValue(),
            'status' => $model->getStatus()->getValue(),
            'name' => $model->getName()->getValue(),
            'order' => $model->getOrder()->getValue(),
        ];
        if ($maybeId->isPseudo()) {
            $id = Database::table('cars')->insert($data)['id'];
        }
        else {
            $id = $maybeId->getValue();
            Database::table('cars')->where('id', '=', $id)->update($data);
        }
        return CarValueId::of($id);
    }

    /**
     * @param CarValueId $id
     * @return Car
     * @throws NotFoundException
     */
    public function findById(CarValueId $id): Car
    {
        $data = Database::table('cars')->where('id', '=', $id->getValue())->first();
        if ($data === null) {
            throw new NotFoundException('Not found');
        }
        return new Car(
            CarValueId::of($data['id']),
            $this->vendorRepository->findById(VendorValueId::of($data['vendor_id'])),
            CarValueVin::of($data['vin']),
            CarValueStatus::of($data['status']),
            CarValueName::of($data['name']),
            CarValueOrder::of($data['order'])
        );
    }

    /**
     * @param array{
     *      @type int[]|null  $vendor_id
     *      @type string|null $vin
     *      @type int[]|null  $status
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by
     *      @type string|null $order
     * } $params
     * @return CarCollection
     */
    public function query(array $params): CarCollection
    {
        $table = Database::table('cars');

        if (!empty($params)) {
            if ($vendorIds = Arr::pull($params, 'vendor_id')) {
                $table->where('vendor_id', 'IN', $vendorIds);
            }
            if ($vin = Arr::pull($params, 'vin')) {
                $table->where('vin', 'LIKE', $vin);
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
            return new CarCollection($results);
        }

        $results = \array_map(function ($row) {
            return new Car(
                CarValueId::of($row['id']),
                $this->vendorRepository->findById(VendorValueId::of($row['vendor_id'])),
                CarValueVin::of($row['vin']),
                CarValueStatus::of($row['status']),
                CarValueName::of($row['name']),
                CarValueOrder::of($row['order'])
            );
        }, $results, []); // 2nd empty array for reassign keys

        return new CarCollection($results);
    }

    /**
     * Delete all stored data
     *
     * @return void
     */
    public function flush(): void
    {
        Database::table('cars')->refresh();
    }
}
