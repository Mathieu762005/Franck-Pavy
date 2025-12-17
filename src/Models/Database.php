<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    /**
     * Crée et retourne une connexion PDO à la base de données
     * @return PDO|null Retourne l'objet PDO ou null si erreur
     */
    public static function createInstancePDO(): PDO|null
    {
        // Récupération des informations de connexion depuis les variables d'environnement
        $db_host = $_ENV['DB_HOST'];
        $db_user = $_ENV['DB_USER'];
        $db_password = $_ENV['DB_PASSWORD'];

        // On choisit la base selon l'environnement (test ou développement)
        $db_name = $_ENV['APP_ENV'] === 'test'
            ? $_ENV['DB_NAME_TEST']
            : $_ENV['DB_NAME_DEV'];

        try {
            // Création d'une instance PDO avec encodage UTF-8
            $pdo = new PDO(
                "mysql:host=$db_host;dbname=$db_name;charset=utf8",
                $db_user,
                $db_password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION] // Active les exceptions pour les erreurs SQL
            );

            return $pdo; // Retourne la connexion
        } catch (PDOException $e) {
            // Si la connexion échoue, retourne null
            // Ici on pourrait logguer $e->getMessage() pour debug
            return null;
        }
    }
}