<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Domain\Kitchencar\KitchencarCollection;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;

class VendorServiceGetKitchencars
{
    /**
     * @var KitchencarRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @access private
     */
    private function __construct()
    {
        $this->repository = doSystem()->make(KitchencarRepositoryInterface::class);
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
     * @return KitchencarCollection
     */
    public static function exec(Vendor $model, array $params): KitchencarCollection
    {
        $params['vendor_id'] = [$model->getId()->getValue()];
        $params['order_by'] ?? $params['order'] = 'order';
        return self::instance()->repository->query($params);
    }
}
