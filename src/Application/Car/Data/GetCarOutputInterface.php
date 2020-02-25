<?php

namespace DoSystem\Application\Car\Data;

use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueId;
use DoSystem\Domain\Car\Model\CarValueName;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

interface GetCarOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Car\Service\GetCarService::handle(int $id)
     *
     * @param Car $model
     */
    public function __construct(Car $model);

    /**
     * @return CarValueId
     */
    public function getId(): CarValueId;

    /**
     * @todo
     */
    // public function belongsTo();

    /**
     * @return ValueObjectVin
     */
    public function getVin(): ValueObjectVin;

    /**
     * @return CarValueName
     */
    public function getName(): CarValueName;
}
