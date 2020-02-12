<?php

namespace DoSystem\Module\Mock\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;

class SampleValueObjectInherited extends SampleValueObject implements ValueObjectInterface
{
    private $value;
}
