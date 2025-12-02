<?php
namespace App\Models;

use PDO;
use PDOException;

class Order
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Créer une commande brouillon
    public function create(int $userId, float $totalPrice, string $pickupTime): int
    {
        $orderNumber = uniqid("CMD_");
        try {
            $stmt = $this->db->prepare("
                INSERT INTO orders (order_number, order_date, order_total_price, order_pickup_time, order_status, user_id)
                VALUES (?, NOW(), ?, ?, 'brouillon', ?)
            ");
            $stmt->execute([$orderNumber, $totalPrice, $pickupTime, $userId]);
            return (int)$this->db->lastInsertId();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // Confirmer une commande
    public function confirm(int $orderId): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE orders SET order_status = 'confirmée' WHERE order_id = ?");
            return $stmt->execute([$orderId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer une commande
    public function getById(int $orderId): ?array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE order_id = ?");
            $stmt->execute([$orderId]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    // Récupérer toutes les commandes d’un utilisateur
    public function getByUser(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM orders WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}