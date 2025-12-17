<?php
namespace App\Models;

use PDO;
use PDOException;

class Product
{
    private PDO $db; // Connexion à la base

    // Propriétés qui correspondent aux colonnes de la table "products"
    public int $id;
    public string $name;
    public string $description;
    public float $price;
    public bool $available;
    public string $image;
    public int $categoryId;

    // Constructeur : on passe la connexion PDO depuis le controller
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les produits disponibles (disponibles = product_available = 1)
     * @return array Tableau de produits
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM products WHERE product_available = 1");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne tous les produits sous forme de tableau associatif
        } catch (PDOException $e) {
            return []; // En cas d'erreur, retourne un tableau vide
        }
    }

    /**
     * Récupère les produits d'une catégorie spécifique
     * @param int $categoryId
     * @return array Tableau de produits
     */
    public function getByCategory(int $categoryId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE category_id = :category_id");
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT); // Protection contre injection SQL
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne les produits de la catégorie
        } catch (PDOException $e) {
            return []; // En cas d'erreur, retourne un tableau vide
        }
    }

    /**
     * Récupère un produit par son ID
     * @param int $productId
     * @return array|null Retourne le produit ou null si non trouvé
     */
    public function getById(int $productId): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->execute([$productId]); // On passe l'ID en paramètre pour sécuriser la requête
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null; // Retourne null si aucun produit trouvé
        } catch (PDOException $e) {
            return null; // En cas d'erreur, retourne null
        }
    }
}