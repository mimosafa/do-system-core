<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\CreateCarInputInterface;

class CreateCarInputMock implements CreateCarInputInterface
{
    /**
     * @var int
     */
    public $vendorId;

    /**
     * @var string
     */
    public $vin;

    /**
     * @var int|null
     */
    public $status;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @return int
     */
    public function getVendorId(): int
    {
        return $this->vendorId;
    }

    /**
     * @return string
     */
    public function getVin(): string
    {
        return $this->vin;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
