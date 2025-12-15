<?php

namespace App\Models;

// on utilise `use` pour indiquer qu'il s'agit de globales PHP et non de classe
use PDO;
use PDOException;


class Database
{
    /**
     * Permet de créer une instance de PDO
     * @return object Instance PDO ou Null
     */
    public static function createInstancePDO(): PDO|null
    {
        $db_host = $_ENV['DB_HOST'];
        $db_user = $_ENV['DB_USER'];
        $db_password = $_ENV['DB_PASSWORD'];

        $db_name = $_ENV['APP_ENV'] === 'test'
            ? $_ENV['DB_NAME_TEST']
            : $_ENV['DB_NAME_DEV'];

        try {
            $pdo = new PDO(
                "mysql:host=$db_host;dbname=$db_name;charset=utf8",
                $db_user,
                $db_password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            return $pdo;
        } catch (PDOException $e) {
            return null;
        }
    }
}