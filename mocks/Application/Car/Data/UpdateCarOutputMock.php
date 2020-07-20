<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\UpdateCarOutputInterface;
use DoSystem\Core\Domain\Car\Car;

class UpdateCarOutputMock implements UpdateCarOutputInterface
{
    /**
     * @var Car
     */
    public $model;

    /**
     * Class names of modified property
     *
     * @var string[]
     */
    public $modified;

    /**
     * Constructor
     *
     * @param Car $model
     * @param string[] $modified
     */
    public function __construct(Car $model, array $modified)
    {
        $this->model = $model;
        $this->modified = $modified;
    }
}
