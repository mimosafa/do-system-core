<?php

namespace DoSystemMock\Application\Vendor\Data;

use DoSystem\Application\Vendor\Data\UpdateVendorOutputInterface;
use DoSystem\Domain\Vendor\Model\Vendor;

class UpdateVendorOutputMock implements UpdateVendorOutputInterface
{
    /**
     * @var Vendor
     */
    public $model;

    /**
     * @var string[]
     */
    public $modified;

    /**
     * Constructor
     *
     * @param Vendor $model
     * @param string[] $modified
     */
    public function __construct(Vendor $model, array $modified)
    {
        $this->model = $model;
        $this->modified = $modified;
    }
}
