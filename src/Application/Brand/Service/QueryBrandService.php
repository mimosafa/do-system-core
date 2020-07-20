<?php

namespace DoSystem\Application\Brand\Service;

use DoSystem\Application\Brand\Data\QueriedBrandOutputInterface;
use DoSystem\Application\Brand\Data\QueryBrandFilterInterface;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;

class QueryBrandService
{
    /**
     * @var BrandRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param BrandRepositoryInterface $repository
     */
    public function __construct(BrandRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param QueryBrandFilterInterface $filter
     * @return QueriedBrandOutputInterface[]
     */
    public function handle(QueryBrandFilterInterface $filter): array
    {
        $params = [];

        if ($vendorFilter = $filter->getVendorIdFilter()) {
            $params['vendor_id'] = $vendorFilter;
        }
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
            return doSystem()->makeWith(QueriedBrandOutputInterface::class, ['model' => $model]);
        })->all();
    }
}
