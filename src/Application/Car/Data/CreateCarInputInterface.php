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
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @return string|null
     */
    public function getName(): ?string;
}
