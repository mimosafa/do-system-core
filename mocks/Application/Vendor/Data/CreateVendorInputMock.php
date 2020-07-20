<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Core\Application\Vendor\Data\CreateVendorInputInterface;

class CreateVendorInputMock implements CreateVendorInputInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int|null
     */
    public $status;

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
