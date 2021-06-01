<?php

namespace App\test;

use PHPUnit\Framework\TestCase;
use App\Database;
// phpcs:ignore PSR12.Files.ImportStatement
use \PDO;

final class DatabaseTest extends TestCase
{
    public function testDatabaseConfiguration()
    {
        $this->assertEquals(true, is_array(Database::configuration()));
    }

    public function testDatabaseConnection()
    {
        $this->assertInstanceOf(PDO::class, Database::connection());
    }
}
