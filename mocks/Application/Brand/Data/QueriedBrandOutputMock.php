<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Application\Brand\Data\QueriedBrandOutputInterface;
use DoSystem\Core\Domain\Brand\Brand;

class QueriedBrandOutputMock implements QueriedBrandOutputInterface
{
    /**
     * @var Brand
     */
    public $model;

    /**
     * Constructor
     *
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        $this->model = $model;
    }
}
