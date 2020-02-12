<?php

namespace DoSystem\Domain\Vendor\Model;

use DoSystem\Module\Domain\Model\AbstractValueObjectEnum;
use DoSystem\Module\Domain\Model\ValueObjectEnumInterface;

final class VendorValueStatus extends AbstractValueObjectEnum implements ValueObjectEnumInterface
{
    /**
     * Constants as Enums
     */
    private const PROSPECTIVE  = 0; // 見込み
    private const UNREGISTERED = 1; // 申請中
    private const PENDING      = 2; // 保留中
    private const REGISTERED   = 3; // 登録済
    private const ACTIVE       = 4; // 活動中
    private const INACTIVE     = 5; // 停滞中
    private const LEAVING      = 6; // 撤退申請中
    private const SUSPENDED    = 7; // 停止中
    private const DEREGISTERED = 8; // 撤退
    private const UNRELATED    = 9; // 無関係

    /**
     * Default status
     */
    private const DEFAULT_STATUS = self::PROSPECTIVE;

    /**
     * @return self
     */
    public static function defaultStatus(): self
    {
        return self::of(self::DEFAULT_STATUS);
    }
}
