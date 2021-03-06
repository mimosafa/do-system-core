<?php

namespace DoSystem\Core\Application\Brand\Data;

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

    /**
     * @return int|null
     */
    public function getOrder(): ?int;
}
