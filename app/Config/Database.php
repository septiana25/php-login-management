<?php 
namespace IanSeptiana\PHP\MVC\LOGIN\Config;

use PDO;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConection(string $env = 'test'): PDO
    {
        if (self::$pdo == NULL) {
            require_once __DIR__ . '/../../config/database.php';
            $config = getDatabaseConfig();
            self::$pdo = new PDO(   $config['database'][$env]['dns'],
                                    $config['database'][$env]['user'],
                                    $config['database'][$env]['pass']);
        }

        return self::$pdo;
    }

    public static function closeDB(){
        self::$pdo =null;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollBackTransaction()
    {
        self::$pdo->rollBack();
    }
}