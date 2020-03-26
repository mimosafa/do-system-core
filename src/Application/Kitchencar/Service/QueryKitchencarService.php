<?php

namespace DoSystem\Application\Kitchencar\Service;

use DoSystem\Application\Kitchencar\Data\QueriedKitchencarOutputInterface;
use DoSystem\Application\Kitchencar\Data\QueryKitchencarFilterInterface;
use DoSystem\Domain\Kitchencar\Model\KitchencarRepositoryInterface;

class QueryKitchencarService
{
    /**
     * @var KitchencarRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param KitchencarRepositoryInterface $repository
     */
    public function __construct(KitchencarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param QueryKitchencarFilterInterface $filter
     * @return QueriedKitchencarOutputInterface[]
     */
    public function handle(QueryKitchencarFilterInterface $filter): array
    {
        $params = [];

        if ($brandFilter = $filter->getBrandFilter()) {
            $params['brand_id'] = $brandFilter;
        }
        if ($carFilter = $filter->getCarFilter()) {
            $params['car_id'] = $carFilter;
        }
        if ($vendorFilter = $filter->getVendorFilter()) {
            $params['vendor_id'] = $vendorFilter;
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
            return doSystem()->makeWith(QueriedKitchencarOutputInterface::class, ['model' => $model]);
        })->all();
    }
}
