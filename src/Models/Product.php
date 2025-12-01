<?php

// On indique que cette classe appartient au dossier logique "Models"
namespace App\Models;

// On importe la classe Database pour se connecter à la base
use App\Models\DataBase;

// On importe les classes PDO pour exécuter des requêtes SQL
use PDO;
use PDOException;

// Définition de la classe Product
class Product
{

    public int $id;
    public string $name;
    public string $description;
    public string $price;
    public string $available;
    public string $image;
    public string $categoryId;


    public function getProductsByCategory(int $categoryId)
    {
        try {
            // Connexion à la base via notre classe Database
            $pdo = Database::createInstancePDO();

            // Si la connexion échoue, on retourne false
            if (!$pdo) {
                return false;
            }

            $sql = "SELECT product_id, product_name, product_description, product_price, product_image
                    FROM products WHERE category_id = :category_id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_OBJ);

        } catch (PDOException $e) {
            // En cas d'erreur SQL, on affiche le message et on retourne false
            // echo 'Erreur : ' . $e->getMessage();
            return false;
        }
    }

}
