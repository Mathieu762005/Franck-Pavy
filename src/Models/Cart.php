<?php
namespace App\Models;

use PDO;
use PDOException;

class Cart
{
    private PDO $db;            // Connexion à la base de données
    private Cart $cart;         // Propriété pour manipuler le panier (si besoin)
    private Product $productModel; // Modèle produit pour récupérer les infos produit

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les produits d’un utilisateur
     */
    public function getUserCart(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les produits non commandés d’un utilisateur
     */
    public function getAllItems(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ? AND order_id IS NULL");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un produit spécifique dans le panier d’un utilisateur
     */
    public function getItemByProductId(int $userId, int $productId): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            return $item ?: null; // Retourne null si non trouvé
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return null;
        }
    }

    /**
     * Ajoute un produit dans le panier
     */
    public function addItem(int $userId, int $productId, string $productName, float $unitPrice, int $quantity): bool
    {
        $totalPrice = $unitPrice * $quantity;

        try {
            $stmt = $this->db->prepare("
                INSERT INTO cart_items 
                (user_id, order_id, product_id, product_name, cart_items_quantity, cart_items_unit_price, cart_items_total_price)
                VALUES (?, NULL, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$userId, $productId, $productName, $quantity, $unitPrice, $totalPrice]);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Met à jour la quantité et le prix d’un produit du panier
     */
    public function updateItem(int $cartItemId, int $quantity, float $unitPrice): bool
    {
        $totalPrice = $quantity * $unitPrice;

        $stmt = $this->db->prepare("
            UPDATE cart_items
            SET cart_items_quantity = :quantity,
                cart_items_total_price = :total
            WHERE cart_item_id = :id
        ");
        return $stmt->execute([
            ':quantity' => $quantity,
            ':total' => $totalPrice,
            ':id' => $cartItemId
        ]);
    }

    /**
     * Supprime un produit du panier
     */
    public function removeItem(int $cartItemId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
            return $stmt->execute([$cartItemId]);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    /**
     * Vide le panier d’un utilisateur
     */
    public function clearUserCart(int $userId): bool
    {
        $sql = "DELETE FROM cart_items WHERE user_id = :userId AND order_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Récupère un item du panier via son ID
     */
    public function getCartItemById(int $cartItemId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE cart_item_id = ?");
        $stmt->execute([$cartItemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item ?: null;
    }
}