<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Domain\Vendor\Model\Vendor;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

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
     * @var ValueObjectVin
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
     * Constructor
     *
     * @param CarValueId $id
     * @param Vendor $vendor
     * @param ValueObjectVin $vin
     * @param CarValueStatus $status
     * @param CarValueName $name
     */
    public function __construct(CarValueId $id, Vendor $vendor, ValueObjectVin $vin, CarValueStatus $status, CarValueName $name)
    {
        $this->id     = $id;
        $this->vendor = $vendor;
        $this->vin    = $vin;
        $this->status = $status;
        $this->name   = $name;
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
     * @return ValueObjectVin
     */
    public function getVin(): ValueObjectVin
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
}
