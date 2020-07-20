<?php

namespace DoSystem\Application\Kitchencar\Data;

use DoSystem\Core\Domain\Kitchencar\Kitchencar;

interface GetKitchencarOutputInterface
{
    /**
     * Constructor
     *
     * ** note **
     * Parameter must be named '$model'
     * @see DoSystem\Application\Kitchencar\Service\GetKitchencarService::handle($id)
     *
     * @param Kitchencar $model
     */
    public function __construct(Kitchencar $model);
}
