<?php

namespace DoSystemCoreMock\Application\Brand\Data;

use DoSystem\Core\Application\Brand\Data\UpdateBrandOutputInterface;
use DoSystem\Core\Domain\Brand\Brand;

class UpdateBrandOutputMock implements UpdateBrandOutputInterface
{
    /**
     * @var Brand
     */
    public $model;

    /**
     * @var string[]
     */
    public $modified;

    /**
     * Constructor
     *
     * @param Brand $model
     * @param string[] $modified
     */
    public function __construct(Brand $model, array $modified)
    {
        $this->model = $model;
        $this->modified = $modified;
    }
}
