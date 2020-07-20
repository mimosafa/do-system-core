<?php

namespace DoSystem\Application\Car\Data;

use DoSystem\Core\Domain\Car\Car;
use DoSystem\Core\Domain\Car\CarValueId;
use DoSystem\Core\Domain\Car\CarValueName;
use DoSystem\Core\Domain\Car\CarValueVin;

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
     * @return CarValueVin
     */
    public function getVin(): CarValueVin;

    /**
     * @return CarValueName
     */
    public function getName(): CarValueName;
}
