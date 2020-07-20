<?php

namespace DoSystem\Core\Application\Brand\Service;

use DoSystem\Core\Application\Brand\Data\GetBrandOutputInterface;
use DoSystem\Core\Domain\Brand\BrandRepositoryInterface;
use DoSystem\Core\Domain\Brand\BrandValueId;

class GetBrandService
{
    /**
     * @var BrandRepositoryInterface
     */
    private $repository;

    /**
     * Constructor
     *
     * @param BrandRepositoryInterface $repository
     */
    public function __construct(BrandRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param BrandValueId $id
     * @return GetBrandOutputInterface
     */
    public function handle(BrandValueId $id): GetBrandOutputInterface
    {
        $model = $this->repository->findById($id);
        return doSystem()->makeWith(GetBrandOutputInterface::class, ['model' => $model]);
    }
}
