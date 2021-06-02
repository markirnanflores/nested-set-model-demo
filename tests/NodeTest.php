<?php

namespace App\test;

use PHPUnit\Framework\TestCase;
use App\Node;
// phpcs:ignore PSR12.Files.ImportStatement
use \PDO;

final class NodeTest extends TestCase
{
    /**
     * Test if Node::findAll returns an array
     */
    public function testFindAll()
    {
        $this->assertEquals(
            true,
            is_array(
                Node::findAll(
                    5,
                    'english',
                    0,
                    100
                )
            )
        );
    }

    /**
     * Test if Node::findFiltered returns an array
     */
    public function testFindFiltered()
    {
        $this->assertEquals(
            true,
            is_array(
                Node::findFiltered(
                    5,
                    'english',
                    'euro',
                    0,
                    100
                )
            )
        );
    }
}
