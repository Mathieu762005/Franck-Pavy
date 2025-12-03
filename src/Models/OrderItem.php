<?php
namespace App\Models;

use PDO;
use PDOException;

class OrderItem
{
    private PDO $db;
    private string $table = 'order_items';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Copier le panier dans order_items
    public function copyCartToOrder(int $orderId, int $userId): bool
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, total_price)
                SELECT ?, product_id, product_name, cart_items_quantity, cart_items_unit_price, cart_items_total_price
                FROM cart_items
                WHERE order_id IS NULL AND user_id = ?
            ");
            return $stmt->execute([$orderId, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer les items d’une commande
    public function getByOrder(int $orderId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->execute([$orderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function deleteByOrder(int $orderId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE order_id = :order_id");
        return $stmt->execute(['order_id' => $orderId]);
    }
}