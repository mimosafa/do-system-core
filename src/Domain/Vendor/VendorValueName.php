<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Module\Domain\AbstractValueObjectString;
use DoSystem\Core\Module\Domain\ValueObjectStringInterface;

final class VendorValueName extends AbstractValueObjectString implements ValueObjectStringInterface
{
    /**
     * @var bool
     */
    protected static $allowEmpty = false;
}
