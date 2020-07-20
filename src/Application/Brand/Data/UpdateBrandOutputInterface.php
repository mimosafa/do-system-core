<?php

namespace DoSystem\Application\Brand\Data;

use DoSystem\Domain\Brand\Model\Brand;

interface UpdateBrandOutputInterface
{
    /**
     * Constructor
     *
     * ** Note **
     * Parameter 1 & 2 must be named '$model' & '$modified'
     * @see DoSystem\Application\Brand\Service\UpdateBrandService::handle()
     *
     * @param Brand $model
     * @param string[] $modified Class names of modified property
     */
    public function __construct(Brand $model, array $modified);
}
