<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Core\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Core\Domain\Car\Car;
use DoSystem\Core\Domain\Car\CarValueVin;

class QueriedCarOutputMock implements QueriedCarOutputInterface
{
    /**
     * @var Car
     */
    public $model;

    /**
     * Constructor
     *
     * @param Car $model
     */
    public function __construct(Car $model)
    {
        $this->model = $model;
    }

    /**
     * @return CarValueVin
     */
    public function getVin(): CarValueVin
    {
        return $this->model->getVin();
    }
}
