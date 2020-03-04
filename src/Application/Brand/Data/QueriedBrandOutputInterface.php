<?php

namespace DoSystem\Application\Brand\Data;

use DoSystem\Domain\Brand\Model\Brand;

interface QueriedBrandOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Brand\Service\QueryBrandService::handle()
     *
     * @param Brand $model
     */
    public function __construct(Brand $model);
}
