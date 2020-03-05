<?php

namespace DoSystem\Domain\Item\Model;

use DoSystem\Domain\Vendor\Model\Vendor;

class Item
{
    /**
     * @var ItemValueId
     */
    private $id;

    /**
     * @var Vendor
     */
    private $vendor;

    /**
     * @var ItemValueName
     */
    private $name;

    /**
     * @var ItemValueStatus
     */
    private $status;
}
