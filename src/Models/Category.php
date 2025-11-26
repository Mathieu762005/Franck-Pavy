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
            // Connexion à la base via notre classe Database
            $pdo = Database::createInstancePDO();

            // Si la connexion échoue, on retourne false
            if (!$pdo) {
                return false;
            }

            $sql = "SELECT category_id, category_name, category_description 
                FROM categories WHERE category_id = :id";

            $stmt = $pdo->prepare($sql);

            // On lie les valeurs aux paramètres SQL
            $stmt->bindValue(':id', $id, PDO::PARAM_INT); // rôle par défaut

            $stmt->execute();

            $category = $stmt->fetch(PDO::FETCH_OBJ);

            return $category ?: null;

        } catch (PDOException $e) {
            // En cas d'erreur SQL, on affiche le message et on retourne false
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }

    }
}