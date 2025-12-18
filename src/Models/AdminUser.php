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

    public function deleteUser(int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :id");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            return $stmt->execute(); // Retourne true si la suppression a réussi
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ici si besoin
            return false;
        }
    }

    public function searchByNameOrFirstname(string $search): array
    {
        // Nettoyage de l'entrée
        $search = trim($search);

        // On split les mots pour chercher indépendamment
        $words = explode(' ', $search);

        $query = "SELECT * FROM users WHERE 1=1";
        $params = [];

        foreach ($words as $index => $word) {
            $query .= " AND (user_name LIKE :w$index OR user_first_name LIKE :w$index)";
            $params[":w$index"] = "%$word%";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
