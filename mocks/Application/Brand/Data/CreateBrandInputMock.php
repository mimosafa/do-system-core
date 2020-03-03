<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Application\Brand\Data\CreateBrandInputInterface;

class CreateBrandInputMock implements CreateBrandInputInterface
{
    /**
     * @var int
     */
    public $vendorId;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int|null
     */
    public $status;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }
}
