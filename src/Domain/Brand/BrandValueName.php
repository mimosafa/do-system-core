<?php

namespace DoSystem\Core\Domain\Brand;

use DoSystem\Core\Module\Domain\AbstractValueObjectString;
use DoSystem\Core\Module\Domain\ValueObjectStringInterface;

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
