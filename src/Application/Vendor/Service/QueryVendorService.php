<?php

namespace DoSystem\Core\Application\Vendor\Service;

use DoSystem\Core\Application\Vendor\Data\QueriedVendorOutputInterface;
use DoSystem\Core\Application\Vendor\Data\QueryVendorFilterInterface;
use DoSystem\Core\Domain\Vendor\VendorRepositoryInterface;

class QueryVendorService
{
    /**
     * @var VendorRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param VendorRepositoryInterface $repository
     */
    public function __construct(VendorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param QueryVendorFilterInterface $filter
     * @return QueriedVendorOutputInterface[]
     */
    public function handle(QueryVendorFilterInterface $filter): array
    {
        $params = [];

        if ($nameFilter = $filter->getNameFilter()) {
            $params['name'] = $nameFilter;
        }
        if ($statusFilter = $filter->getStatusFilter()) {
            $params['status'] = $statusFilter;
        }

        if ($size = $filter->getSizePerPage()) {
            $params['size_per_page'] = $size;
            $params['page'] = $filter->getPage() ?? 1;
        }

        if ($orderBy = $filter->getOrderBy()) {
            $params['order_by'] = $orderBy;
            $params['order'] = $filter->getOrder() ?? 'asc';
        }

        $collection = $this->repository->query($params);

        return $collection->map(function ($model) {
            return doSystem()->makeWith(QueriedVendorOutputInterface::class, ['model' => $model]);
        })->all();
    }
}
