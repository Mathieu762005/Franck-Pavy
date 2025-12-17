<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class AdminProduct
{
    private PDO $db; // Connexion à la base de données

    public function __construct()
    {
        // On utilise la classe Database pour créer une instance PDO
        $this->db = DataBase::createInstancePDO();
    }

    /**
     * Récupère tous les produits avec leur catégorie
     * @return array Tableau associatif des produits ou vide en cas d'erreur
     */
    public function findAll(): array
    {
        try {
            // Requête SQL pour récupérer les produits avec le nom de la catégorie
            $sql = "
                SELECT products.product_id, products.product_name, products.product_available, categories.category_name
                FROM products
                JOIN categories ON products.category_id = categories.category_id;
            ";
            $stmt = $this->db->query($sql); // Exécute la requête
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (PDOException $e) {
            // En cas d'erreur SQL, retourne un tableau vide
            return [];
        }
    }
}