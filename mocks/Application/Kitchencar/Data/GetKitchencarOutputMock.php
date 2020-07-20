<?php

namespace DoSystemMock\Application\Kitchencar\Data;

use DoSystem\Core\Application\Kitchencar\Data\GetKitchencarOutputInterface;
use DoSystem\Core\Domain\Kitchencar\Kitchencar;

class GetKitchencarOutputMock implements GetKitchencarOutputInterface
{
    /**
     * @var
     */
    public $model;

    /**
     * Constructor
     *
     * @param Kitchencar $model
     */
    public function __construct(Kitchencar $model)
    {
        $this->model = $model;
    }
}
