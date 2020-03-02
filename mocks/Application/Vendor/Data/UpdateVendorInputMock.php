<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\UpdateVendorInputInterface;

class UpdateVendorInputMock implements UpdateVendorInputInterface
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var int|null
     */
    public $status;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
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
