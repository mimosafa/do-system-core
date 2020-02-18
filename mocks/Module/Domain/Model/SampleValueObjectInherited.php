<?php

namespace DoSystemMock\Module\Domain\Model;

use DoSystem\Module\Domain\Model\ValueObjectInterface;

class SampleValueObjectInherited extends SampleValueObject implements ValueObjectInterface
{
    private $value;
}
