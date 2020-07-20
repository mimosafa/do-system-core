<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Domain\Car\Model\CarCollection;
use DoSystem\Domain\Car\Model\CarRepositoryInterface;

class VendorGetCars
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
