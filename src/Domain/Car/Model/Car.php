<?php

namespace DoSystem\Domain\Car\Model;

class Car
{
    /**
     * @var CarValueId|null
     */
    private $id;

    /**
     * @var CarValueVin
     */
    private $vin;

    /**
     * @return CarValueId|null
     */
    public function getId(): ?CarValueId
    {
        return $this->id;
    }

    /**
     * @return CarValueVin
     */
    public function getVin(): CarValueVin
    {
        return $this->vin;
    }
}
