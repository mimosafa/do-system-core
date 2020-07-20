<?php

namespace DoSystemMock\Application\Brand\Data;

use DoSystem\Domain\Brand\Model\Brand;
use DoSystem\Application\Brand\Data\GetBrandOutputInterface;

class GetBrandOutputMock implements GetBrandOutputInterface
{
    /**
     * @var \DoSystem\Domain\Brand\Model\BrandValueId
     */
    public $id;

    /**
     * @var \DoSystem\Domain\Vendor\Model\Vendor
     */
    public $vendor;

    /**
     * @var \DoSystem\Domain\Brand\Model\BrandValueName
     */
    public $name;

    /**
     * @var \DoSystem\Domain\Brand\Model\BrandValueStatus
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
