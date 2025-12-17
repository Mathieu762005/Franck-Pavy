<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class Category
{
    // Propriétés correspondant aux colonnes de la table categories
    public int $id;           // ID de la catégorie
    public string $name;      // Nom de la catégorie
    public string $description; // Description de la catégorie

    /**
     * Récupère une catégorie via son ID
     * @param int $id ID de la catégorie
     * @return array|false Tableau associatif avec les infos de la catégorie ou false si non trouvé
     */
    public function getCategoryById(int $id)
    {
        try {
            // Crée une connexion PDO via le modèle Database
            $pdo = Database::createInstancePDO();
            if (!$pdo)
                return false;

            // Préparation de la requête SQL
            $sql = "SELECT category_id, category_name, category_description 
                    FROM categories 
                    WHERE category_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Exécution de la requête
            $stmt->execute();

            // Récupération du résultat sous forme de tableau associatif
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retourne false si aucune catégorie trouvée
            if (!$category)
                return false;

            return $category;

        } catch (PDOException $e) {
            // En cas d'erreur SQL, retourne false
            return false;
        }
    }
}