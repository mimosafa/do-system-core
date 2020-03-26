<?php

namespace DoSystem\Application\Kitchencar\Data;

use DoSystem\Domain\Kitchencar\Model\Kitchencar;

interface QueriedKitchencarOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Kitchencar\Service\QueryKitchencarService::handle()
     *
     * @param Kitchencar $model
     */
    public function __construct(Kitchencar $model);
}
