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
     * @var BrandValueOrder
     */
    private $order;

    /**
     * Constructor
     *
     * @param BrandValueId $id
     * @param Vendor $vendor
     * @param BrandValueName $name
     * @param BrandValueStatus $status
     * @param BrandValueOrder $order
     */
    public function __construct(BrandValueId $id, Vendor $vendor, BrandValueName $name, BrandValueStatus $status, BrandValueOrder $order)
    {
        $this->id = $id;
        $this->vendor = $vendor;
        $this->name = $name;
        $this->status = $status;
        $this->order = $order;
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

    /**
     * @return BrandValueOrder
     */
    public function getOrder(): BrandValueOrder
    {
        return $this->order;
    }

    /**
     * @param BrandValueName $name
     * @return bool
     */
    public function setName(BrandValueName $name): bool
    {
        if (!$name->equals($this->name)) {
            $this->name = $name;
            return true;
        }
        return false;
    }

    /**
     * @param BrandValueOrder $order
     * @return bool
     */
    public function setOrder(BrandValueOrder $order): bool
    {
        if (!$order->equals($this->order)) {
            $this->order = $order;
            return true;
        }
        return false;
    }
}
