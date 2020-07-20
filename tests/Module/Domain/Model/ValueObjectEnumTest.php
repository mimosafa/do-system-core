<?php

namespace DoSystemTests\Module\Domain\Model;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Module\Domain\ValueObjectEnumInterface;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObjectEnum;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObjectEnumInherited;

class ValueObjectEnumTest extends TestCase
{
    /**
     * @var ValueObjectEnumInterface
     */
    protected $vo1;
    protected $vo2;
    protected $voInherited;

    /**
     * @var int
     * @see SampleValueObjectEnum
     */
    // `key` will be 'MALE'
    // `label` will be 'Male'
    protected $val1 = 1;
    // `key` will be 'NOT_APPLICABLE'
    // `label` will be 'LGBTQ'
    protected $val2 = 9;

    protected function setUp(): void
    {
        $this->vo1 = SampleValueObjectEnum::of($this->val1);
        $this->vo2 = SampleValueObjectEnum::of($this->val2);
        $this->voInherited = SampleValueObjectEnumInherited::of($this->val1);
    }

    /**
     * @test
     */
    public function testConstruct()
    {
        $this->assertTrue($this->vo1 instanceof ValueObjectEnumInterface);
    }

    /**
     * @test
     */
    public function testEquals()
    {
        $vo1Dash = SampleValueObjectEnum::of($this->val1);
        $this->assertTrue($this->vo1->equals($vo1Dash));
        $this->assertFalse($this->vo2->equals($vo1Dash));
        // Inherited enum class
        $this->assertFalse($this->vo1->equals($this->voInherited));
    }

    /**
     * @test
     */
    public function testToArray()
    {
        /**
         * @see SampleValueObjectEnum
         */
        $expectedArray = [
            'NOT_KNOWN'      => 0,
            'MALE'           => 1,
            'FEMALE'         => 2,
            'NOT_APPLICABLE' => 9,
        ];

        $this->assertSame($this->vo1::toArray(), $expectedArray);
    }

    /**
     * @test
     */
    public function testValues()
    {
        $vo0 = SampleValueObjectEnum::of(0);
        $vo1 = SampleValueObjectEnum::of(1);
        $vo2 = SampleValueObjectEnum::of(2);
        $vo9 = SampleValueObjectEnum::of(9);

        $expected = [
            $vo0->getKey() => $vo0,
            $vo1->getKey() => $vo1,
            $vo2->getKey() => $vo2,
            $vo9->getKey() => $vo9,
        ];

        $this->assertEquals(SampleValueObjectEnum::values(), $expected);
    }

    /**
     * @test
     */
    public function testGetValue()
    {
        $this->assertEquals($this->vo1->getValue(), $this->val1);
        $this->assertEquals($this->vo2->getValue(), $this->val2);
    }

    /**
     * @test
     */
    public function testGetKey()
    {
        /**
         * @see SampleValueObjectEnum
         */
        $this->assertEquals($this->vo1->getKey(), 'MALE');
        $this->assertEquals($this->vo2->getKey(), 'NOT_APPLICABLE');
    }

    /**
     * @test
     */
    public function testGetLabel()
    {
        $this->assertEquals($this->vo1->getLabel(), 'Male');
        $this->assertEquals($this->vo2->getLabel(), 'LGBTQ');
    }

    /**
     * @test
     */
    public function testIsValid()
    {
        $this->assertTrue(SampleValueObjectEnum::isValid(0));
        $this->assertFalse(SampleValueObjectEnum::isValid(100));
    }

    /**
     * @test
     */
    public function testIsValidKey()
    {
        $this->assertTrue(SampleValueObjectEnum::isValidKey('MALE'));
        $this->assertFalse(SampleValueObjectEnum::isValidKey('JAPANESE'));
    }

    /**
     * @test
     */
    public function testMagicMethodIsOneEnum()
    {
        $this->assertTrue($this->vo1->isMale());
        $this->assertFalse($this->vo1->isFemale());

        $this->assertTrue($this->vo2->isNotApplicable());
        $this->assertFalse($this->vo2->isMale());
    }
}
