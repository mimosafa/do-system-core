<?php

namespace DoSystemCoreTests\Module\Domain;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Module\Domain\ValueObjectInterface;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObject;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObjectInherited;

class ValueObjectTest extends TestCase
{
    protected $vo1;
    protected $val1 = 100;
    protected $vo2;
    protected $val2 = 'Awesome text.';

    protected $vo1Inherited;

    protected function setUp(): void
    {
        $this->vo1 = SampleValueObject::of($this->val1);
        $this->vo2 = SampleValueObject::of(SampleValueObject::of($this->val2));
        $this->vo1Inherited = SampleValueObjectInherited::of($this->val1);
    }

    /**
     * @test
     */
    public function testConstruct()
    {
        $this->assertTrue($this->vo1 instanceof ValueObjectInterface);
        $this->assertTrue($this->vo2 instanceof ValueObjectInterface);
    }

    /**
     * @test
     */
    public function testEquals()
    {
        $vo1Dash = SampleValueObject::of($this->val1);
        $vo2Dash = SampleValueObject::of($this->val2);

        $this->assertTrue($this->vo1->equals($vo1Dash));
        $this->assertTrue($this->vo2->equals($vo2Dash));
        $this->assertFalse($this->vo1->equals($this->vo2));
        $this->assertFalse($this->vo1->equals($this->vo1Inherited));
    }

    /**
     * @test
     */
    public function testToString()
    {
        $this->assertNotSame((string) $this->vo1, 100);
        $this->assertSame((string) $this->vo1, '100');
    }
}
