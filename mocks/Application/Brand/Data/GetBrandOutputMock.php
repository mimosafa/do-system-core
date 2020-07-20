<?php

namespace DoSystemCoreMock\Application\Brand\Data;

use DoSystem\Core\Domain\Brand\Brand;
use DoSystem\Core\Application\Brand\Data\GetBrandOutputInterface;

class GetBrandOutputMock implements GetBrandOutputInterface
{
    /**
     * @var \DoSystem\Core\Domain\Brand\BrandValueId
     */
    public $id;

    /**
     * @var \DoSystem\Core\Domain\Vendor\Vendor
     */
    public $vendor;

    /**
     * @var \DoSystem\Core\Domain\Brand\BrandValueName
     */
    public $name;

    /**
     * @var \DoSystem\Core\Domain\Brand\BrandValueStatus
     */
    public $status;

    /**
     * Constructor
     *
     * @param Brand $model
     */
    public function __construct(Brand $model)
    {
        $this->id = $model->getId();
        $this->vendor = $model->belongsTo();
        $this->name = $model->getName();
        $this->status = $model->getStatus();
    }
}
