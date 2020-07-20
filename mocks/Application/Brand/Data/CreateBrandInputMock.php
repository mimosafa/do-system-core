<?php

namespace DoSystemCoreMock\Application\Brand\Data;

use DoSystem\Core\Application\Brand\Data\CreateBrandInputInterface;

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
     * @var int|null
     */
    public $order;

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

    /**
     * @return int|null
     */
    public function getOrder(): ?int
    {
        return $this->order;
    }
}
