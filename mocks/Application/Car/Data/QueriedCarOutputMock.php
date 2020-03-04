<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Car\Model\CarValueVin;

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
