<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class AdminUser
{
    private PDO $db; // Connexion à la base de données

    public function __construct()
    {
        // On utilise la classe Database pour créer une instance PDO
        $this->db = DataBase::createInstancePDO();
    }

    /**
     * Récupère tous les utilisateurs
     * @return array Tableau associatif des utilisateurs ou vide en cas d'erreur
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM users"; // Requête SQL pour récupérer tous les utilisateurs
            $stmt = $this->db->query($sql); // Exécute la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (PDOException $e) {
            // En cas d'erreur SQL, retourne un tableau vide
            return [];
        }
    }
}