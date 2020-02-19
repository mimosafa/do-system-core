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
     * @var CarValueName
     */
    private $name;

    /**
     * Constructor
     *
     * @param CarValueId $id
     * @param Vendor $vendor
     * @param CarValueVin $vin
     * @param CarValueName $name
     */
    public function __construct(CarValueId $id, Vendor $vendor, CarValueVin $vin, CarValueName $name)
    {
        $this->id = $id;
        $this->vendor = $vendor;
        $this->vin = $vin;
        $this->name = $name;
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
     * @return CarValueName
     */
    public function getName(): CarValueName
    {
        return $this->name;
    }
}
