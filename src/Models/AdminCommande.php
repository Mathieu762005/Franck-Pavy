<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class AdminCommande
{
    private PDO $db; // Connexion à la base de données

    public function __construct()
    {
        // On utilise la classe Database pour créer une instance PDO
        $this->db = DataBase::createInstancePDO();
    }

    /**
     * Récupère toutes les commandes
     * @return array Tableau associatif des commandes
     */
    public function findAll(): array
    {
        try {
            // Requête SQL pour récupérer toutes les commandes triées par date décroissante
            $stmt = $this->db->query("SELECT * FROM orders ORDER BY order_date DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (PDOException $e) {
            // En cas d'erreur SQL, retourne un tableau vide
            return [];
        }
    }
}