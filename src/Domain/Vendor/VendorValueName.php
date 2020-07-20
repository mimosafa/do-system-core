<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Module\Domain\Model\AbstractValueObjectString;
use DoSystem\Module\Domain\Model\ValueObjectStringInterface;

final class VendorValueName extends AbstractValueObjectString implements ValueObjectStringInterface
{
    /**
     * @var bool
     */
    protected static $allowEmpty = false;
}
