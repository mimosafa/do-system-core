<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Domain\Status\Model\AbstractValueObjectStatus;
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
