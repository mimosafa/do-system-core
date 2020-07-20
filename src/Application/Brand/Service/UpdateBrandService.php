<?php

namespace DoSystem\Application\Brand\Service;

use DoSystem\Application\Brand\Data\UpdateBrandInputInterface;
use DoSystem\Application\Brand\Data\UpdateBrandOutputInterface;
use DoSystem\Domain\Brand\Model\BrandRepositoryInterface;
use DoSystem\Domain\Brand\Model\BrandValueId;
use DoSystem\Domain\Brand\Model\BrandValueName;

class UpdateBrandService
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
     * @param UpdateBrandInputInterface $input
     * @return UpdateBrandOutputInterface
     */
    public function handle(UpdateBrandInputInterface $input): UpdateBrandOutputInterface
    {
        $model = $this->repository->findById(BrandValueId::of($input->getId()));

        $nameValue = $input->getName();

        $modified = [];

        if ($nameValue !== null) {
            $done = $model->setName(BrandValueName::of($nameValue));
            if ($done) {
                $modified[] = BrandValueName::class;
            }
        }

        if (!empty($modified)) {
            $id = $this->repository->store($model);
        }

        return doSystem()->makeWith(UpdateBrandOutputInterface::class, [
            'model' => $this->repository->findById($id),
            'modified' => $modified
        ]);
    }
}
