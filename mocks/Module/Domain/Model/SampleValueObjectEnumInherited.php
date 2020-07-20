<?php

namespace DoSystemCoreMock\Module\Domain\Model;

use DoSystem\Core\Module\Domain\ValueObjectEnumInterface;

class SampleValueObjectEnumInherited extends SampleValueObjectEnum implements ValueObjectEnumInterface
{
    /**
     * Enums
     */
    private const NOT_KNOWN      = 0;
    private const MALE           = 1;
    private const FEMALE         = 2;
    private const NOT_APPLICABLE = 9;
}
