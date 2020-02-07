<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    public function testSample()
    {
        $this->assertTrue(class_exists('DoSystem\\Domain\\Models\\User\\User'));
    }
}
