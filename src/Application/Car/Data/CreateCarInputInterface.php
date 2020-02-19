<?php

namespace DoSystem\Application\Car\Data;

interface CreateCarInputInterface
{
    /**
     * @return int
     */
    public function getVendorId(): int;

    /**
     * @return string
     */
    public function getVin(): string;

    /**
     * @return string|null
     */
    public function getName(): ?string;
}
