<?php

namespace DoSystemTest\Domain\Vin\Model;

use PHPUnit\Framework\TestCase;
use DoSystem\Domain\Vin\Model\ValueObjectVin;

class ValueObjectVinTest extends TestCase
{
    /**
     * @test
     */
    public function testIsValid()
    {
        $this->assertTrue(ValueObjectVin::isValid('多摩500さ4649'));
        $this->assertTrue(ValueObjectVin::isValid('なにわ000Y0'));
        $this->assertFalse(ValueObjectVin::isValid('500あ4649'));
    }
}
