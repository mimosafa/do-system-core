<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\QueryVendorFilterInterface;

class QueryVendorFilterMock implements QueryVendorFilterInterface
{
    private $name;
    private $status;
    private $sizePerPage;
    private $page;

    /**
     * Constructor
     *
     * @param string|null $name
     * @param int[]|null $status
     * @param int|null $sizePerPage
     * @param int|null $page
     */
    public function __construct(?string $name = null, ?array $status = null, ?int $sizePerPage = null, ?int $page = null)
    {
        $this->name = $name;
        $this->status = $status;
        $this->sizePerPage = $sizePerPage;
        $this->page = $page;
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
