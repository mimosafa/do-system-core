<?php

namespace DoSystem\Core\Application\Vendor\Data;

interface CreateVendorInputInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;
}
