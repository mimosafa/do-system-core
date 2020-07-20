<?php

namespace DoSystem\Core\Application\Vendor\Data;

interface UpdateVendorInputInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;
}
