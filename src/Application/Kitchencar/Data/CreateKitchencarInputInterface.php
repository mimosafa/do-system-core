<?php

namespace DoSystem\Application\Kitchencar\Data;

interface CreateKitchencarInputInterface
{
    /**
     * @return int
     */
    public function getBrandId(): int;

    /**
     * @return int
     */
    public function getCarId(): int;

    /**
     * @return int|null
     */
    public function getOrder(): ?int;
}
