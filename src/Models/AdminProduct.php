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

    public function deleteProduit($produitId)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = :id");
            $stmt->bindValue(':id', $produitId, PDO::PARAM_INT);
            return $stmt->execute(); // Retourne true si la suppression a réussi
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ici si besoin
            return false;
        }
    }

    // Modifier un produit complet
    public function updateProduit(
        int $productId,
        string $name,
        string $subtitle,
        string $description,
        float $price,
        string $image,
        int $categoryId,
        int $stock
    ): bool {
        $sql = "
        UPDATE products
        SET 
            product_name = :name,
            product_subtitle = :subtitle,
            product_description = :description,
            product_price = :price,
            product_image = :image,
            category_id = :category,
            product_available = :stock
        WHERE product_id = :id
    ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'name'     => $name,
            'subtitle' => $subtitle,
            'description' => $description,
            'price'    => $price,
            'image'    => $image,
            'category' => $categoryId,
            'stock'    => $stock,
            'id'       => $productId
        ]);
    }

    public function updateStock(int $productId, int $stock): bool
    {
        $sql = "UPDATE products SET product_available = :stock WHERE product_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'stock' => $stock,
            'id' => $productId
        ]);
    }
}
