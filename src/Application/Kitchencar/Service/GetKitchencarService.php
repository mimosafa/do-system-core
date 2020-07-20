<?php

namespace DoSystem\Application\Kitchencar\Service;

use DoSystem\Application\Kitchencar\Data\GetKitchencarOutputInterface;
use DoSystem\Core\Domain\Kitchencar\KitchencarRepositoryInterface;
use DoSystem\Core\Domain\Kitchencar\KitchencarValueId;

class GetKitchencarService
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
     * @param KitchencarValueId $id
     * @return GetKitchencarOutputInterface
     */
    public function handle(KitchencarValueId $id): GetKitchencarOutputInterface
    {
        $model = $this->repository->findById($id);
        return doSystem()->makeWith(GetKitchencarOutputInterface::class, ['model' => $model]);
    }
}
