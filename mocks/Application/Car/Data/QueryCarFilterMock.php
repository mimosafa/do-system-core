<?php

namespace DoSystemMock\Application\Car\Data;

use DoSystem\Application\Car\Data\QueryCarFilterInterface;

class QueryCarFilterMock implements QueryCarFilterInterface
{
    private $vendorId;
    private $vin;
    private $status;
    private $sizePerPage;
    private $page;

    /**
     * Constructor
     */
    public function __construct(?array $vendorId = null, ?string $vin = null, ?array $status = null, ?int $sizePerPage = null, ?int $page = null)
    {
        $this->vendorId = $vendorId;
        $this->vin = $vin;
        $this->status = $status;
        $this->sizePerPage = $sizePerPage;
        $this->page = $page;
    }

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
