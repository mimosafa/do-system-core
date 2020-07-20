<?php

namespace DoSystem\Domain\Brand\Model;

use DoSystem\Module\Domain\Model\AbstractValueObjectString;
use DoSystem\Module\Domain\Model\ValueObjectStringInterface;

final class BrandValueName extends AbstractValueObjectString implements ValueObjectStringInterface
{
    /**
     * @var int
     */
    protected static $maxLength = 100;

    /**
     * @var bool
     */
    protected static $allowEmpty = false;
}
