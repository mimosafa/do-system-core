<?php

namespace DoSystemMock\Infrastructure\Repository;

use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;
use DoSystem\Core\Domain\Car\Car;
use DoSystem\Core\Domain\Car\CarCollection;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Kitchencar\Kitchencar;
use DoSystem\Core\Domain\Kitchencar\KitchencarCollection;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueId;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueOrder;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;
use DoSystem\Core\Domain\Vendor\VendorValueId;
use DoSystem\Core\Exception\NotFoundException;
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
        //
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
