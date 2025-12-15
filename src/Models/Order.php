<?php
namespace App\Models;

use PDO;
use PDOException;

class Order
{
    private PDO $db;
    private string $table = 'orders';

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
            return (int) $this->db->lastInsertId();
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

    public function updateTotalPrice(int $orderId): bool
    {
        $stmt = $this->db->prepare("
            UPDATE orders o
            JOIN (
                SELECT order_id, SUM(total_price) AS total
                FROM order_items
                WHERE order_id = ?
                GROUP BY order_id
            ) oi ON o.order_id = oi.order_id
            SET o.order_total_price = oi.total
            WHERE o.order_id = ?
        ");
        return $stmt->execute([$orderId, $orderId]);
    }

    public function updateStatus(int $orderId, string $status): bool
    {
        try {
            $sql = "UPDATE `orders` SET `order_status` = :status WHERE `order_id` = :orderId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete(int $orderId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE order_id = :order_id");
        return $stmt->execute(['order_id' => $orderId]);
    }

    // Compter le nombre de réservations sur un créneau donné
    public function getReservationCount(string $timeSlot): int
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE order_pickup_time = ?");
            $stmt->execute([$timeSlot]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function markAsPaid(int $orderId): bool
    {
        $stmt = $this->db->prepare("UPDATE orders SET order_status = 'payée' WHERE order_id = :order_id");
        return $stmt->execute([':order_id' => $orderId]);
    }
}