<?php

namespace DoSystem\Core\Application\Car\Data;

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

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string;

    /**
     * @return string|null
     */
    public function getOrder(): ?string;
}
