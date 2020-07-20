<?php

namespace DoSystem\Core\Application\Car\Data;

use DoSystem\Core\Domain\Car\Car;

interface QueriedCarOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Core\Application\Car\Service\QueryCarService::handle()
     *
     * @param Car $model
     */
    public function __construct(Car $model);
}
