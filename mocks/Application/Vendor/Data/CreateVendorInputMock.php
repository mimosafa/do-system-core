<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\CreateVendorInputInterface;

class CreateVendorInputMock implements CreateVendorInputInterface
{
    private $id;
    private $name;
    private $status;

    public function __construct($id, $name, $status)
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = $status;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}
