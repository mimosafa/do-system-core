<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Application\Brand\Data\UpdateBrandOutputInterface;
use DoSystem\Domain\Brand\Model\Brand;

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
