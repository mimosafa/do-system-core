<?php

namespace DoSystem\Application\Car\Data;

interface QueryCarFilterInterface
{
    /**
     * @return int[]|null
     */
    public function getVendorIdFilter(): ?array;

    /**
     * @return string|null
     */
    public function getVinFilter(): ?string;

    /**
     * @return int[]|null
     */
    public function getStatusFilter(): ?array;

    /**
     * @return int|null
     */
    public function getSizePerPage(): ?int;

    /**
     * @return int|null
     */
    public function getPage(): ?int;
}
