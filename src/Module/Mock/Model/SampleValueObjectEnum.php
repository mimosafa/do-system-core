<?php

namespace DoSystem\Module\Mock\Model;

use DoSystem\Module\Domain\Model\AbstractValueObjectEnum;
use DoSystem\Module\Domain\Model\ValueObjectEnumInterface;

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
     * Static class constants
     */
    private const SAMPLE_CONSTANT = 'sample constant';

    /**
     * Specific name string of enums
     *
     * @var array
     */
    protected $labels = [
        0 => 'Unknown',
        9 => 'LGBTQ',
    ];

    /**
     * Exclude constants from enums
     */
    protected static $excludedFromEnums = [
        'SAMPLE_CONSTANT',
    ];
}
