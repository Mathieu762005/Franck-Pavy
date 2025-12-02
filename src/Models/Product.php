<?php
namespace App\Models;

use PDO;
use PDOException;

class Product
{
    private PDO $db;

    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public bool $available;
    public string $image;
    public int $categoryId;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Récupérer tous les produits disponibles
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM products WHERE product_available = 1");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Récupérer les produits par catégorie
    public function getByCategory(int $categoryId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE category_id = :category_id");
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Récupérer un produit par ID
    public function getById(int $productId): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$productId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}