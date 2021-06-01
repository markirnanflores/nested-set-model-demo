<?php

namespace App\test;

use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    public function testIndexFileExist()
    {
        $indexFile = dirname(__DIR__, 1) . '/html/index.php';
        $this->assertFileExists($indexFile);
    }

    public function testConfigFileExist()
    {
        $configFile = dirname(__DIR__, 1) . '/config.php';
        $this->assertFileExists($configFile);
    }
}
