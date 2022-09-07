<?php
namespace IanSeptiana\PHP\MVC\LOGIN;

use IanSeptiana\PHP\MVC\LOGIN\Config\Database;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testConnection(){
        $conn = Database::getConection();
        self::assertNotNull($conn);
    }

    public function testConnSinglton(){
        $conn = Database::getConection();
        $conn1 = Database::getConection();

        self::assertSame($conn, $conn1);
    }

}