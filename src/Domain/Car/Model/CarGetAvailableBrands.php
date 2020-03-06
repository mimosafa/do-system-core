<?php

namespace DoSystem\Domain\Car\Model;

use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Kitchencar\Model\KitchencarRepositoryInterface;

class CarGetAvailableBrands
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
     * @param Car $model
     * @param array $params
     * @return BrandCollection
     */
    public static function exec(Car $model, array $params): BrandCollection
    {
        return self::instance()->repository->findBrandsByCar($model, $params);
    }
}
