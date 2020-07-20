<?php

namespace DoSystemTest\Domain\Prefecture\Model;

use PHPUnit\Framework\TestCase;
use DoSystem\Core\Domain\Prefecture\Prefecture;
use DoSystem\Core\Domain\Prefecture\PrefectureValueId;
use DoSystem\Core\Domain\Prefecture\PrefectureValueName;
use DoSystem\Core\Domain\Prefecture\PrefectureRepositoryInterface;

class PrefectureTest extends TestCase
{
    /**
     * @var PrefectureRepositoryInterface
     */
    private static $repository;

    public static function setUpBeforeClass(): void
    {
        self::$repository = doSystem()->make(PrefectureRepositoryInterface::class);
    }

    /**
     * @test
     */
    public function testFindById()
    {
        $kochi = self::$repository->findById(PrefectureValueId::of(39));
        $name = $kochi->getName();

        $this->assertTrue($kochi instanceof Prefecture);
        $this->assertTrue($name instanceof PrefectureValueName);
        $this->assertEquals($name->getValue(), '高知県');
    }

    /**
     * @test
     */
    public function testFindByName()
    {
        $tokyo = self::$repository->findByName(PrefectureValueName::of('東京都'));
        $id = $tokyo->getId();

        $this->assertTrue($tokyo instanceof Prefecture);
        $this->assertTrue($id instanceof PrefectureValueId);
        $this->assertEquals($id->getValue(), 13);
    }
}
