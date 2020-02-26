<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\GetCarOutputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Car\Model\CarValueVin;

class GetCarOutputMock implements GetCarOutputInterface
{
    /**
     * @var CarValueId
     */
    public $id;

    /**
     * @todo
     */
    public $belongsTo;

    /**
     * @var CarValueVin
     */
    public $vin;

    /**
     * @var CarValueName
     */
    public $name;

    /**
     * Constructor
     *
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        $this->id = $model->getId();
        // $this->belongsTo = $model->belongsTo();
        $this->vin = $model->getVin();
        $this->name = $model->getName();
    }

    public function getId(): CarValueId
    {
        return $this->id;
    }

    // public function belongsTo() {}

    public function getVin(): CarValueVin
    {
        return $this->vin;
    }

    public function getName(): CarValueName
    {
        return $this->name;
    }
}
