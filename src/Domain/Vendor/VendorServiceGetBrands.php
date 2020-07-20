<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Domain\Brand\BrandCollection;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;

class VendorServiceGetBrands
{
    /**
     * @var BrandRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @access private
     */
    private function __construct()
    {
        $this->repository = doSystem()->make(BrandRepositoryInterface::class);
    }

    /**
     * Singleton
     *
     * @static
     * @access private
     *
     * @return self
     */
    private static function instance(): self
    {
        static $instance;
        return $instance ?? $instance = new self();
    }

    /**
     * @param Vendor $model
     * @param array $params
     * @return BrandCollection
     */
    public static function exec(Vendor $model, array $params = []): BrandCollection
    {
        $params['vendor_id'] = [$model->getId()->getValue()];
        $params['order_by'] ?? $params['order_by'] = 'order';
        return self::instance()->repository->query($params);
    }
}
