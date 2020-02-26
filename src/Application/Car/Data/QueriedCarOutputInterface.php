<?php

namespace DoSystem\Application\Car\Data;

use DoSystem\Domain\Car\Model\Car;

interface QueriedCarOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Car\Service\QueryCarService::handle()
     *
     * @param Car $model
     */
    public function __construct(Car $model);
}
