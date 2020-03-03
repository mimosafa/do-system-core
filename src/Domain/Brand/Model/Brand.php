<?php

namespace DoSystem\Domain\Brand\Model;

use DoSystem\Domain\Vendor\Model\Vendor;

class Brand
{
    /**
     * @var BrandValueId
     */
    private $id;

    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * @var BrandValueName
     */
    private $name;

    /**
     * @var BrandValueStatus
     */
    private $status;

    /**
     * Constructor
     *
     * @param BrandValueId $id
     * @param Vendor $vendor
     * @param BrandValueName $name
     * @param BrandValueStatus $status
     */
    public function __construct(BrandValueId $id, Vendor $vendor, BrandValueName $name, BrandValueStatus $status)
    {
        $this->id = $id;
        $this->vendor = $vendor;
        $this->name = $name;
        $this->status = $status;
    }

    /**
     * @return BrandValueId
     */
    public function getId(): BrandValueId
    {
        return $this->id;
    }

    /**
     * @return Vendor
     */
    public function belongsTo(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return BrandValueName
     */
    public function getName(): BrandValueName
    {
        return $this->name;
    }

    /**
     * @return BrandValueStatus
     */
    public function getStatus(): BrandValueStatus
    {
        return $this->status;
    }
}
