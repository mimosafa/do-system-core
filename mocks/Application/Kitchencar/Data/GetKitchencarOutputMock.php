<?php

namespace DoSystemMock\Application\Kitchencar\Data;

use DoSystem\Application\Kitchencar\Data\GetKitchencarOutputInterface;
use DoSystem\Domain\Kitchencar\Model\Kitchencar;

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
