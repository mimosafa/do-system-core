<?php

namespace DoSystem\Core\Application\Brand\Data;

use DoSystem\Core\Domain\Brand\Brand;

interface UpdateBrandOutputInterface
{
    /**
     * Constructor
     *
     * ** Note **
     * Parameter 1 & 2 must be named '$model' & '$modified'
     * @see DoSystem\Core\Application\Brand\Service\UpdateBrandService::handle()
     *
     * @param Brand $model
     * @param string[] $modified Class names of modified property
     */
    public function __construct(Brand $model, array $modified);
}
