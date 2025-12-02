<?php

// On indique que cette classe appartient au dossier logique "Models"
namespace App\Models;

// On importe la classe Database pour se connecter à la base
use App\Models\DataBase;

// On importe les classes PDO pour exécuter des requêtes SQL
use PDO;
use PDOException;

// Définition de la classe User
class Category
{
    // Propriétés du User (correspondent aux colonnes de la table "users")
    public int $id;
    public string $name;
    public string $description;


    // Récupérer une catégorie par son id
    public function getCategoryById($id)
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "SELECT category_id, category_name, category_description 
                FROM categories 
                WHERE category_id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            // On récupère la ligne en tableau associatif
            $category = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si aucun résultat
            if (!$category) {
                return false;
            }

            return $category;

        } catch (PDOException $e) {
            return false;
        }
    }
}