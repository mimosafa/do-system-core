<?php

namespace DoSystem\Core\Domain\Car;

use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Vendor\Vendor;

class Car
{
    /**
     * @var CarValueId|null
     */
    private $id;

    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * @var CarValueVin
     */
    private $vin;

    /**
     * @var CarValueStatus
     */
    private $status;

    /**
     * @var CarValueName
     */
    private $name;

    /**
     * @var CarValueOrder
     */
    private $order;

    /**
     * Constructor
     *
     * @param CarValueId $id
     * @param Vendor $vendor
     * @param CarValueVin $vin
     * @param CarValueStatus $status
     * @param CarValueName $name
     */
    public function __construct(CarValueId $id, Vendor $vendor, CarValueVin $vin, CarValueStatus $status, CarValueName $name, CarValueOrder $order)
    {
        $this->id     = $id;
        $this->vendor = $vendor;
        $this->vin    = $vin;
        $this->status = $status;
        $this->name   = $name;
        $this->order  = $order;
    }

    /**
     * @return CarValueId
     */
    public function getId(): CarValueId
    {
        return $this->id;
    }

    /**
     * @return Vendor
     */
    public function belongsTo(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return CarValueVin
     */
    public function getVin(): CarValueVin
    {
        return $this->vin;
    }

    /**
     * @return CarValueStatus
     */
    public function getStatus(): CarValueStatus
    {
        return $this->status;
    }

    /**
     * @return CarValueName
     */
    public function getName(): CarValueName
    {
        return $this->name;
    }

    /**
     * @return CarValueOrder
     */
    public function getOrder(): CarValueOrder
    {
        return $this->order;
    }

    /**
     * @param array $params
     * @return BrandCollection
     */
    public function getAvailableBrands(array $params = []): BrandCollection
    {
        return CarGetAvailableBrands::exec($this, $params);
    }

    /**
     * @param CarValueVin $vin
     * @return bool If no change, return false
     */
    public function setVin(CarValueVin $vin): bool
    {
        if (!$vin->equals($this->vin)) {
            $this->vin = $vin;
            return true;
        }
        return false;
    }

    /**
     * @param CarValueStatus $status
     * @return bool
     */
    public function setStatus(CarValueStatus $status): bool
    {
        if (!$status->equals($this->status)) {
            $this->status = $status;
            return true;
        }
        return false;
    }

    /**
     * @param CarValueName $name
     * @return bool
     */
    public function setName(CarValueName $name): bool
    {
        if (!$name->equals($this->name)) {
            $this->name = $name;
            return true;
        }
        return false;
    }

    /**
     * @param CarValueOrder $order
     * @return bool
     */
    public function setOrder(CarValueOrder $order): bool
    {
        if (!$order->equals($this->order)) {
            $this->order = $order;
            return true;
        }
        return false;
    }
}
