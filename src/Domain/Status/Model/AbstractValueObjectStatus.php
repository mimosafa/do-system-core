<?php

namespace DoSystem\Domain\Status\Model;

use DoSystem\Module\Domain\Model\AbstractValueObjectEnum;

/**
 * Common status value object for 'Vendor', 'Car', and 'Brand'
 */
abstract class AbstractValueObjectStatus extends AbstractValueObjectEnum
{
    /**
     * Statuses as enums
     *
     * @var int
     */
    protected const PROSPECTIVE  = 0;
    protected const UNREGISTERED = 1;
    protected const PENDING      = 2;
    protected const REGISTERED   = 3;
    protected const REJECTED     = 4;
    protected const INACTIVE     = 5;
    protected const LEAVING      = 6;
    protected const SUSPENDED    = 7;
    protected const DEREGISTERED = 8;
    protected const UNRELATED    = 9;

    /**
     * Default status
     *
     * @var int|null  One of status constants of class
     */
    protected static $defaultStatus;

    /**
     * @return self
     */
    public static function default(): self
    {
        return isset(static::$defaultStatus) ? static::of(static::$defaultStatus) : \array_values(static::values())[0];
    }
}
