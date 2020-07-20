<?php

namespace DoSystemCoreTest\Module\Domain\Model;

use PHPUnit\Framework\TestCase;
use DoSystemCoreMock\Module\Domain\Model\SampleValueObjectFile;
use DOSYSTEM_TESTS_ROOT_DIR;

class ValueObjectFileTest extends TestCase
{
    /**
     * Temporary dir & file
     *
     * @var string
     */
    private static $root;
    private static $dummy;

    public static function setUpBeforeClass(): void
    {
        self::$root  = DOSYSTEM_TESTS_ROOT_DIR . '/tmp';
        self::$dummy = self::$root . '/dummy.txt';
        if (!\file_exists(self::$root)) {
            \mkdir(self::$root);
        }
        \touch(self::$dummy);
    }

    public static function tearDownAfterClass(): void
    {
        \unlink(self::$dummy);
        \rmdir(self::$root);
    }

    /**
     * @test
     */
    public function testConstruct(): SampleValueObjectFile
    {
        $dummy = SampleValueObjectFile::of(self::$dummy);

        $this->assertTrue($dummy instanceof SampleValueObjectFile);

        return $dummy;
    }

    /**
     * @test
     * @depends testConstruct
     */
    public function testExists(SampleValueObjectFile $dummy)
    {
        $this->assertTrue($dummy->exists());

        $notExist = '/path/to/file';
        $notExistFile = SampleValueObjectFile::of($notExist);

        $this->assertFalse($notExistFile->exists());
    }
}
