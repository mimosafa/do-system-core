<?php

namespace DoSystemCoreMock\Module\Domain\Model;

use DoSystem\Core\Module\Domain\ValueObjectInterface;

class SampleValueObjectInherited extends SampleValueObject implements ValueObjectInterface
{
    private $value;
}
