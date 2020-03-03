<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\QueryCarFilterInterface;

class QueryCarFilterMock implements QueryCarFilterInterface
{
    /**
     * @var int[]|null
     */
    public $vendorId;

    /**
     * @var string|null
     */
    public $vin;

    /**
     * @var int[]|null
     */
    public $status;

    /**
     * @var int|null
     */
    public $sizePerPage;

    /**
     * @var int|null
     */
    public $page;

    /**
     * @return int[]|null
     */
    public function getVendorIdFilter(): ?array
    {
        return $this->vendorId;
    }

    /**
     * @return string|null
     */
    public function getVinFilter(): ?string
    {
        return $this->vin;
    }

    /**
     * @return int[]|null
     */
    public function getStatusFilter(): ?array
    {
        return $this->status;
    }

    /**
     * @return int|null
     */
    public function getSizePerPage(): ?int
    {
        return $this->sizePerPage;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }
}
