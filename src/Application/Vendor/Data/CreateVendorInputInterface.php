<?php

namespace DoSystem\Application\Vendor\Data;

interface CreateVendorInputInterface
{
    public function getId(): ?int;
    public function getName(): ?string;
    public function getStatus(): ?int;
}
