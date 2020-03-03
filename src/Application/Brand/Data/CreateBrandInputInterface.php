<?php

namespace DoSystem\Application\Brand\Data;

interface CreateBrandInputInterface
{
    /**
     * @return int
     */
    public function getVendorId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;
}
