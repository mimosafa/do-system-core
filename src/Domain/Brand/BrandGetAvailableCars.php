<?php

namespace DoSystem\Core\Domain\Brand;

use DoSystem\Core\Domain\Car\CarCollection;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;

class BrandGetAvailableCars
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
     * @param Brand $model
     * @param array $params
     * @return CarCollection
     */
    public static function exec($model, array $params): CarCollection
    {
        return self::instance()->repository->findCarsByBrand($model, $params);
    }
}
