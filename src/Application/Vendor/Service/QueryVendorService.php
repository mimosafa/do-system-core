<?php

namespace DoSystem\Application\Vendor\Service;

use DoSystem\Application\Vendor\Data\QueriedVendorOutputInterface;
use DoSystem\Application\Vendor\Data\QueryVendorFilterInterface;
use DoSystem\Domain\Vendor\Model\VendorRepositoryInterface;

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

        $collection = $this->repository->query($params);

        return $collection->map(function ($model) {
            return doSystem()->makeWith(QueriedVendorOutputInterface::class, ['model' => $model]);
        })->all();
    }
}
