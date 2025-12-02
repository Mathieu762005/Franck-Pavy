<?php
namespace App\Models;

use PDO;
use PDOException;

class Cart
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Ajouter un produit au panier
    public function addItem(int $userId, int $productId, string $productName, float $unitPrice, int $quantity): bool
    {
        try {
            // Vérifier si le produit est déjà dans le panier pour cet utilisateur
            $stmt = $this->db->prepare("SELECT cart_item_id, cart_items_quantity FROM cart_items WHERE user_id = ? AND product_id = ? AND order_id IS NULL");
            $stmt->execute([$userId, $productId]);
            $existingItem = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingItem) {
                // Produit déjà dans le panier → mettre à jour la quantité et le total
                $newQuantity = $existingItem['cart_items_quantity'] + $quantity;
                $totalPrice = $unitPrice * $newQuantity;

                $updateStmt = $this->db->prepare("
                UPDATE cart_items 
                SET cart_items_quantity = ?, cart_items_total_price = ? 
                WHERE cart_item_id = ?
            ");
                return $updateStmt->execute([$newQuantity, $totalPrice, $existingItem['cart_item_id']]);
            } else {
                // Nouveau produit → insérer dans le panier
                $totalPrice = $unitPrice * $quantity;
                $insertStmt = $this->db->prepare("
                INSERT INTO cart_items 
                (user_id, order_id, product_id, product_name, cart_items_quantity, cart_items_unit_price, cart_items_total_price)
                VALUES (?, NULL, ?, ?, ?, ?, ?)
            ");
                return $insertStmt->execute([$userId, $productId, $productName, $quantity, $unitPrice, $totalPrice]);
            }
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    // Récupérer le panier d’un utilisateur
    public function getUserCart(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM cart_items WHERE order_id IS NULL AND user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Supprimer un item du panier
    public function removeItem(int $cartItemId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
            return $stmt->execute([$cartItemId]);
        } catch (PDOException $e) {
            error_log("Erreur Cart::removeItem - " . $e->getMessage());
            return false;
        }
    }

    // Mettre à jour un item
    public function updateItem(int $cartItemId, int $quantity, float $unitPrice): bool
    {
        $totalPrice = $quantity * $unitPrice;
        try {
            $stmt = $this->db->prepare("
                UPDATE cart_items 
                SET cart_items_quantity = ?, cart_items_unit_price = ?, cart_items_total_price = ?
                WHERE cart_item_id = ?
            ");
            return $stmt->execute([$quantity, $unitPrice, $totalPrice, $cartItemId]);
        } catch (PDOException $e) {
            error_log("Erreur Cart::updateItem - " . $e->getMessage());
            return false;
        }
    }
}