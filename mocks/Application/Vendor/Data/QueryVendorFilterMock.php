<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\QueryVendorFilterInterface;

class QueryVendorFilterMock implements QueryVendorFilterInterface
{
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
