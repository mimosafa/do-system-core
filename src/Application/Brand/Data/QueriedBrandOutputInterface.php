<?php

namespace DoSystem\Core\Application\Brand\Data;

use DoSystem\Core\Domain\Brand\Brand;

interface QueriedBrandOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Core\Application\Brand\Service\QueryBrandService::handle()
     *
     * @param Brand $model
     */
    public function __construct(Brand $model);
}
