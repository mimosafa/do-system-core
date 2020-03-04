<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Application\Brand\Data\QueryBrandFilterInterface;

class QueryBrandFilterMock implements QueryBrandFilterInterface
{
    /**
     * @var int[]|null
     */
    public $vendorId;

    /**
     * @var string|null
     */
    public $name;

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
    public function getNameFilter(): ?string
    {
        return $this->name;
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
