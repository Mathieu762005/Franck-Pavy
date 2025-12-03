<?php
namespace App\Models;

use PDO;
use PDOException;

class Cart
{
    private PDO $db;
    private Cart $cart;       // <--- Déclarer la propriété
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Récupère tous les produits du panier d’un utilisateur
    public function getUserCart(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère **tous les produits** du panier (tous utilisateurs)
    public function getAllItems(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ? AND order_id IS NULL");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un produit spécifique d’un utilisateur via product_id
    public function getItemByProductId(int $userId, int $productId): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE user_id = ? AND product_id = ?");
            $stmt->execute([$userId, $productId]);
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            return $item ?: null;
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return null;
        }
    }

    // Ajoute un produit au panier
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

    // Met à jour un produit existant
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

    // Supprime un produit du panier
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

    public function clearUserCart(int $userId): bool
    {
        $sql = "DELETE FROM cart_items WHERE user_id = :userId AND order_id IS NULL";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}