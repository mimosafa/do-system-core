<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Domain\Car\CarCollection;
use DoSystem\Core\Domain\Car\CarRepositoryInterface;

class VendorServiceGetCars
{
    /**
     * @var CarRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @access private
     */
    private function __construct()
    {
        $this->repository = doSystem()->make(CarRepositoryInterface::class);
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
     * @return CarCollection
     */
    public static function exec(Vendor $model, array $params = []): CarCollection
    {
        $params['vendor_id'] = [$model->getId()->getValue()];
        $params['order_by'] ?? $params['order_by'] = 'order';
        return self::instance()->repository->query($params);
    }
}
