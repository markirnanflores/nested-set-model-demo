<?php

namespace App\test;

use PHPUnit\Framework\TestCase;
use App\Database;
// phpcs:ignore PSR12.Files.ImportStatement
use \PDO;

final class DatabaseTest extends TestCase
{
    /**
     * Check if Database can get the configuration values
     */
    public function testDatabaseConfiguration()
    {
        $this->assertEquals(true, is_array(Database::configuration()));
    }

    /**
     * Check if a connection can be made
     */
    public function testDatabaseConnection()
    {
        $this->assertInstanceOf(PDO::class, Database::connection());
    }
}
