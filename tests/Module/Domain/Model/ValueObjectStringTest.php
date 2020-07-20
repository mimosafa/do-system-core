<?php

namespace DoSystemTest\Module\Domain\Model;

use PHPUnit\Framework\TestCase;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObjectString;

class ValueObjectStringTest extends TestCase
{
    /**
     * @test
     */
    public function testDefinedLength()
    {
        SampleValueObjectString::set('minLength', 2);
        SampleValueObjectString::set('maxLength', 8);

        $this->assertTrue(SampleValueObjectString::isValid('ab'));
        $this->assertTrue(SampleValueObjectString::isValid('あいうえおかきく'));
        $this->assertFalse(SampleValueObjectString::isValid('あ'));
        $this->assertFalse(SampleValueObjectString::isValid('abcdefghi'));

        SampleValueObjectString::init();
    }

    /**
     * @test
     */
    public function testDefinedPattern()
    {
        SampleValueObjectString::set('pattern', '/^[a-zA-Z_]+$/');

        $this->assertTrue(SampleValueObjectString::isValid('Test_string'));
        $this->assertFalse(SampleValueObjectString::isValid('テスト string 100'));

        SampleValueObjectString::init();
    }

    /**
     * @test
     */
    public function testMultibyte()
    {
        $this->assertTrue(SampleValueObjectString::isValid('テスト'));

        SampleValueObjectString::set('multibyte', false);

        $this->assertFalse(SampleValueObjectString::isValid('テスト'));

        SampleValueObjectString::init();
    }

    /**
     * @test
     */
    public function testAllowEmpty()
    {
        $this->assertTrue(SampleValueObjectString::isValid(''));

        SampleValueObjectString::set('allowEmpty', false);

        $this->assertFalse(SampleValueObjectString::isValid(''));

        SampleValueObjectString::init();
    }
}
