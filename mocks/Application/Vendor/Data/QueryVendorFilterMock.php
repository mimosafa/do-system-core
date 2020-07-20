<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Core\Application\Vendor\Data\QueryVendorFilterInterface;

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
     * @var string|null
     */
    public $orderBy;

    /**
     * @var string|null
     */
    public $order;

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

    /**
     * @return string|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @return string|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }
}
