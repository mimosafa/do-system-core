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
        $this->assertTrue(ValueObjectVin::isValid('高知8あ1'));
        
        $this->assertFalse(ValueObjectVin::isValid('東京500あ4649')); // '東京'
        $this->assertFalse(ValueObjectVin::isValid('足立40し1234')); // 'し'
        $this->assertFalse(ValueObjectVin::isValid('習志野300そ034')); // '034'
    }
}
