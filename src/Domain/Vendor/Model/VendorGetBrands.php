<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Domain\Brand\Model\BrandCollection;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;

class VendorGetBrands
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
        return self::instance()->repository->query($params);
    }
}
