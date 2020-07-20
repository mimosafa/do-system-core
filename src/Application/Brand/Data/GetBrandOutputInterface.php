<?php

namespace DoSystem\Application\Brand\Data;

use DoSystem\Core\Domain\Brand\Brand;

interface GetBrandOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Brand\Service\GetBrandService::handle($id)
     *
     * @param Brand $model
     */
    public function __construct(Brand $model);
}
