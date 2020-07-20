<?php

namespace DoSystem\Application\Car\Service;

use DoSystem\Application\Car\Data\QueriedCarOutputInterface;
use DoSystem\Application\Car\Data\QueryCarFilterInterface;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;

class QueryCarService
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param CarRepositoryInterface $repository
     */
    public function __construct(CarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param QueryCarFilterInterface $filter
     * @return QueriedCarOutputInterface[]
     */
    public function handle(QueryCarFilterInterface $filter): array
    {
        $params = [];

        if ($vendorFilter = $filter->getVendorIdFilter()) {
            $params['vendor_id'] = $vendorFilter;
        }
        if ($vinFilter = $filter->getVinFilter()) {
            $params['vin'] = $vinFilter;
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
            return doSystem()->makeWith(QueriedCarOutputInterface::class, ['model' => $model]);
        })->all();
    }
}
