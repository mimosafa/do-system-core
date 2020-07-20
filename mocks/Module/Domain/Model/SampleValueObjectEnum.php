<?php

namespace DoSystemMock\Module\Domain\Model;

use DoSystem\Core\Module\Domain\AbstractValueObjectEnum;
use DoSystem\Core\Module\Domain\ValueObjectEnumInterface;

class SampleValueObjectEnum extends AbstractValueObjectEnum implements ValueObjectEnumInterface
{
    /**
     * Enums
     */
    private const NOT_KNOWN      = 0;
    private const MALE           = 1;
    private const FEMALE         = 2;
    private const NOT_APPLICABLE = 9;

    /**
     * Constant unique to this class, not included in Enums
     * @see self::$excludedFromEnums
     */
    private const SAMPLE_CONSTANT = 'sample constant';

    /**
     * Customized label strings for enum value
     *
     * @var array
     */
    protected $labels = [
        0 => 'Unknown', // default: 'Not Known'
        9 => 'LGBTQ',   // default: 'Not Applicable'
    ];

    /**
     * Exclude constants from enums
     */
    protected static $excludedFromEnums = [
        'SAMPLE_CONSTANT',
    ];
}
