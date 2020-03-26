<?php

namespace DoSystemMock\Infrastructure\Repository;

use Illuminate\Support\Arr;
use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Kitchencar\Model\Kitchencar;
use DoSystem\Domain\Kitchencar\Model\KitchencarCollection;
use DoSystem\Domain\Kitchencar\Model\KitchencarRepositoryInterface;
use DoSystem\Domain\Kitchencar\Model\KitchencarValueId;
use DoSystem\Domain\Kitchencar\Model\KitchencarValueOrder;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;
use DoSystem\Domain\Vendor\Model\VendorValueId;
use DoSystem\Exception\NotFoundException;
use PseudoDatabase\Database;

class InMemoryKitchencarRepository implements KitchencarRepositoryInterface
{
    /**
     * Kitchencars table definition
     */
    private $definitions = [
        'id'       => ['primary' => true],
        'brand_id' => ['type' => 'integer'],
        'car_id'   => ['type' => 'integer'],
        'order'    => ['nullable' => true, 'type' => 'integer'],
    ];

    /**
     * @var BrandRepositoryInterface
     */
    private $brandRepository;

    /**
     * @var CarRepositoryInterface
     */
    private $carRepository;

    /**
     * @var VendorRepositoryInterface
     */
    private $vendorRepository;

    /**
     * Constructor
     *
     * @param BrandRepositoryInterface $brandRepository
     * @param CarRepositoryInterface $carRepository
     * @param VendorRepositoryInterface $vendorRepository
     */
    public function __construct(BrandRepositoryInterface $brandRepository, CarRepositoryInterface $carRepository, VendorRepositoryInterface $vendorRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->carRepository = $carRepository;
        $this->vendorRepository = $vendorRepository;

        if (Database::exists('kitchencars')) {
            return;
        }
        Database::create('kitchencars', $this->definitions);
    }

    /**
     * @param Kitchencar $model
     * @return KitchencarValueId
     */
    public function store(Kitchencar $model): KitchencarValueId
    {
        $maybeId = $model->getId();
        $data = [
            'brand_id' => $model->getBrand()->getId()->getValue(),
            'car_id' => $model->getCar()->getId()->getValue(),
            'order' => $model->getOrder()->getValue(),
        ];
        if ($maybeId->isPseudo()) {
            $id = Database::table('kitchencars')->insert($data)['id'];
        }
        else {
            $id = $maybeId->getValue();
            Database::table('kitchencars')->where('id', '=', $id)->update($data);
        }
        return KitchencarValueId::of($id);
    }

    /**
     * @param KitchencarValueId $id
     * @return Kitchencar
     */
    public function findById(KitchencarValueId $id): Kitchencar
    {
        $data = Database::table('kitchencars')->where('id', '=', $id->getValue())->first();
        if ($data === null) {
            throw new NotFoundException('Not found');
        }
        return new Kitchencar(
            KitchencarValueId::of($data['id']),
            $this->brandRepository->findById(BrandValueId::of($data['brand_id'])),
            $this->carRepository->findById(CarValueId::of($data['car_id'])),
            KitchencarValueOrder::of($data['order'])
        );
    }

    /**
     * @param Car $car
     * @param array $params
     * @return BrandCollection
     */
    public function findBrandsByCar(Car $car, array $params): BrandCollection
    {
        //
    }

    /**
     * @param Brand $brand
     * @param array $params
     * @return CarCollection
     */
    public function findCarsByBrand(Brand $brand, array $params): CarCollection
    {
        //
    }

    /**
     * @param array{
     *      @type int[]|null  $brand_id
     *      @type int[]|null  $car_id
     *      @type int[]|null  $vendor_id
     *      @type int|null    $size_per_page
     *      @type int|null    $page
     *      @type string|null $order_by
     *      @type string|null $order
     * } $params
     * @return KitchencarCollection
     */
    public function query(array $params): KitchencarCollection
    {
        $table = Database::table('kitchencars');

        if (!empty($params)) {
            $brandIds = Arr::pull($params, 'brand_id', []);
            $carIds = Arr::pull($params, 'car_id', []);
            if ($vendorIds = Arr::pull($params, 'vendor_id')) {
                foreach ($vendorIds as $vendorId) {
                    $vendor = $this->vendorRepository->findById(VendorValueId::of($vendorId));
                    $brands = $vendor->getBrands();
                    if ($brands->isNotEmpty()) {
                        foreach ($brands as $brand) {
                            $brandIds[] = $brand->getId()->getValue();
                        }
                    }
                    $car = $vendor->getCars();
                    if ($cars->isNotEmpty()) {
                        foreach ($cars as $car) {
                            $carIds[] = $car->getId()->getValue();
                        }
                    }
                }
            }
            if (!empty($brandIds)) {
                $table->where('brand_id', 'IN', \array_unique($brandIds));
            }
            if (!empty($carIds)) {
                $table->where('car_id', 'IN', \array_filter($carIds));
            }
            if ($size = Arr::pull($params, 'size_per_page')) {
                $page = Arr::pull($params, 'page', 1);
                $start = ($page - 1) * $size;
                $table->offset($start)->limit($size);
            }
            if ($orderBy = Arr::pull($params, 'order_by')) {
                if ($orderBy === 'order') {
                    $table->orderBy($orderBy, Arr::pull($params, 'order'))->isNull('asc');
                }
                else if ($orderBy === 'vendor_id') {
                    $table = $table->join('cars', 'car_id', '=', 'cars.id');
                    $table->orderBy('cars.vendor_id', Arr::pull($params, 'order'));
                }
            }
        }

        $results = $table->get();

        if (empty($results)) {
            return new KitchencarCollection($results);
        }

        $results = \array_map(function ($row) {
            return new Kitchencar(
                KitchencarValueId::of($row['id']),
                $this->brandRepository->findById(BrandValueId::of($row['brand_id'])),
                $this->carRepository->findById(CarValueId::of($row['car_id'])),
                KitchencarValueOrder::of($row['order'])
            );
        }, $results, []); // 2nd empty array for reassign keys

        return new KitchencarCollection($results);
    }

    /**
     * Delete all stored data
     *
     * @return void
     */
    public function flush(): void
    {
        Database::table('kitchencars')->refresh();
    }
}
