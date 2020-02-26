<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Domain\Car\Model\Car;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

class QueriedCarOutputMock implements QueriedCarOutputInterface
{
    /**
     * @var Car
     */
    private $model;

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
     * @return ValueObjectVin
     */
    public function getVin(): ValueObjectVin
    {
        return $this->model->getVin();
    }
}
