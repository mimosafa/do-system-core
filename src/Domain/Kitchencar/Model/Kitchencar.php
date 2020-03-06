<?php

namespace DoSystem\Domain\Kitchencar\Model;

use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Vendor\Model\Vendor;

class Kitchencar
{
    /**
     * @var KitchencarValueId
     */
    private $id;

    /**
     * @var Brand
     */
    private $brand;

    /**
     * @var Car
     */
    private $car;

    /**
     * @var KitchencarValueOrder
     */
    private $order;

    /**
     * Constructor
     *
     * @param KitchencarValueId $id
     * @param Brand $brand
     * @param Car $car
     * @param KitchencarValueOrder $order
     */
    public function __construct(KitchencarValueId $id, Brand $brand, Car $car, KitchencarValueOrder $order)
    {
        if (!$brands->belongsTo()->equals($car->belongsTo())) {
            throw new \Exception();
        }
        $this->id    = $id;
        $this->brand = $brand;
        $this->car   = $car;
        $this->order = $order;
    }

    /**
     * @return KitchencarValueId
     */
    public function getId(): KitchencarValueId
    {
        return $this->id;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }

    /**
     * @return KitchencarValueOrder
     */
    public function getOrder(): KitchencarValueOrder
    {
        return $this->order;
    }

    /**
     * @return Vendor
     */
    public function belongsTo(): Vendor
    {
        return $this->getBrand()->belongsTo();
        # return $this->getCar()->belongsTo(); // Whitchever..
    }
}
