<?php

namespace DoSystem\Core\Domain\Vendor;

use DoSystem\Core\Domain\Status\AbstractValueObjectStatus;
use DoSystem\Module\Domain\Model\ValueObjectEnumInterface;

final class VendorValueStatus extends AbstractValueObjectStatus implements ValueObjectEnumInterface
{
    /**
     * Default status
     *
     * @var int
     */
    protected static $defaultStatus = self::PROSPECTIVE;
}
