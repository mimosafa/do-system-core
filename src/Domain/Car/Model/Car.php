<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Domain\Vendor\Model\Vendor;

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
     * Constructor
     *
     * @param CarValueId $id
     * @param Vendor $vendor
     * @param CarValueVin $vin
     */
    public function __construct(CarValueId $id, Vendor $vendor, CarValueVin $vin)
    {
        $this->id = $id;
        $this->vendor = $vendor;
        $this->vin = $vin;
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
}
