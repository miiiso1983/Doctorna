<?php
namespace App\Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $pdo = null;

    public static function conn(array $config): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['db']['host'],
                $config['db']['port'],
                $config['db']['database'],
                $config['db']['charset'] ?? 'utf8mb4'
            );
            try {
                self::$pdo = new PDO($dsn, $config['db']['username'], $config['db']['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo 'Database connection error: ' . $e->getMessage();
                exit;
            }
        }
        return self::$pdo;
    }
}

