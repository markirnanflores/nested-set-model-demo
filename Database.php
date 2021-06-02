<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    /**
     * Get array value from config.php
     * @return array
     */
    public static function configuration()
    {
        return require(dirname(__FILE__) . '/config.php');
    }

    /**
     * Create a database connection with PDO
     * @return PDO
     */
    public static function connection(string $charset = '')
    {
        $config = self::configuration();

        try {
            $pdo = new PDO(
                "mysql:host=" . $config['host'] . ";dbname=" . $config['db'] . $charset,
                $config['username'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            return false;
        }
    }
}
