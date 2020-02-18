<?php

namespace DoSystem\Domain\Brand\Model;

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
     * Constructor
     *
     * @param BrandValueId $id
     * @param Vendor $vendor
     * @param BrandValueName $name
     */
    public function __construct(BrandValueId $id, Vendor $vendor, BrandValueName $name)
    {
        $this->id = $id;
        $this->vendor = $vendor;
        $this->name = $name;
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
}
