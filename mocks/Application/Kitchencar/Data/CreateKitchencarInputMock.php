<?php

namespace DoSystemMock\Application\Kitchencar\Data;

use DoSystem\Application\Kitchencar\Data\CreateKitchencarInputInterface;

class CreateKitchencarInputMock implements CreateKitchencarInputInterface
{
    /**
     * @var int
     */
    public $brandId;

    /**
     * @var int
     */
    public $carId;

    /**
     * @var int|null
     */
    public $order;

    /**
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }

    /**
     * @return int
     */
    public function getCarId(): int
    {
        return $this->carId;
    }

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }
}
