<?php

namespace DoSystemCoreTest\Domain\Car;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Domain\Car\CarValueVin;

class CarValueVinTest extends TestCase
{
    /**
     * @test
     */
    public function testIsValid()
    {
        $this->assertTrue(CarValueVin::isValid('多摩500さ4649'));
        $this->assertTrue(CarValueVin::isValid('なにわ000Y0'));
        $this->assertTrue(CarValueVin::isValid('高知8あ1'));

        $this->assertFalse(CarValueVin::isValid('東京500あ4649')); // '東京'
        $this->assertFalse(CarValueVin::isValid('足立40し1234')); // 'し'
        $this->assertFalse(CarValueVin::isValid('習志野300そ034')); // '034'
    }
}
