<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Car\CarCollection;
use DoSystem\Core\Domain\Kitchencar\KitchencarCollection;

class Vendor
{
    /**
     * @var VendorValueId
     */
    private $id;

    /**
     * @var VendorValueName
     */
    private $name;

    /**
     * @var VendorValueStatus
     */
    private $status;

    /**
     * Constructor
     *
     * @param VendorValueId $id
     * @param VendorValueName $name
     * @param VendorValueStatus $status
     */
    public function __construct(VendorValueId $id, VendorValueName $name, VendorValueStatus $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    /**
     * @return VendorValueId
     */
    public function getId(): VendorValueId
    {
        return $this->id;
    }

    /**
     * @return VendorValueName
     */
    public function getName(): VendorValueName
    {
        return $this->name;
    }

    /**
     * @return VendorValueStatus
     */
    public function getStatus(): VendorValueStatus
    {
        return $this->status;
    }

    /**
     * @param array $params
     * @return BrandCollection
     */
    public function getBrands(array $params = []): BrandCollection
    {
        return VendorServiceGetBrands::exec($this, $params);
    }

    /**
     * @param array $params
     * @return CarCollection
     */
    public function getCars(array $params = []): CarCollection
    {
        return VendorServiceGetCars::exec($this, $params);
    }

    /**
     * @param array $params
     * @return KitchencarCollection
     */
    public function getKitchencars(array $params = []): KitchencarCollection
    {
        return VendorServiceGetKitchencars::exec($this, $params);
    }

    /**
     * @param VendorValueName $name
     * @return bool
     */
    public function setName(VendorValueName $name): bool
    {
        if (!$name->equals($this->name)) {
            $this->name = $name;
            return true;
        }
        return false;
    }

    /**
     * @param mixed $model
     * @return bool
     */
    public function equals($model): bool
    {
        return $model instanceof self && $model->getId()->equals($this->getId());
    }
}
