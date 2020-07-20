<?php

namespace DoSystem\Core\Application\Brand\Data;

interface QueryBrandFilterInterface
{
    /**
     * @return int[]|null
     */
    public function getVendorIdFilter(): ?array;

    /**
     * @return string|null
     */
    public function getNameFilter(): ?string;

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
