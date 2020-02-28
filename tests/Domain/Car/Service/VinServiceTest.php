<?php

namespace DoSystemTest\Domain\Car\Service;

use PHPUnit\Framework\TestCase;
// use DoSystem\Domain\Car\Model\CarRepositoryInterface;
use DoSystem\Domain\Car\Service\VinService;

class VinServiceTest extends TestCase
{
    /**
     * @todo testExists
     */

    /**
     * @test
     */
    public function testFormat()
    {
        $str1 = '多摩５００さ４６－４９';
        $str2 = 'なにわ　０００Ｙ・・・０';
        $str3 = '習志野 ３００ そ 90-34';
        $invalid1 = 'あ品川88 42-19';
        $invalid2 = '東京５００さ４６－４９';

        $this->assertEquals(VinService::format($str1), '多摩500さ4649');
        $this->assertEquals(VinService::format($str2), 'なにわ000Y0');
        $this->assertEquals(VinService::format($str3), '習志野300そ9034');
        $this->assertEquals(VinService::format($invalid1), '');
        $this->assertEquals(VinService::format($invalid2), '');
    }
}
