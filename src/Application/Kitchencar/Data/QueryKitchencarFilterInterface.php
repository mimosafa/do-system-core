<?php

namespace DoSystem\Application\Kitchencar\Data;

interface QueryKitchencarFilterInterface
{
    /**
     * @return int[]|null
     */
    public function getBrandFilter(): ?array;

    /**
     * @return int[]|null
     */
    public function getCarFilter(): ?array;

    /**
     * @return int[]|null
     */
    public function getVendorFilter(): ?array;

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
