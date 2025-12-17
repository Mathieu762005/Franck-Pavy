<?php
namespace App\Models;

use PDO;
use PDOException;

class Order
{
    private PDO $db;           // Connexion à la base
    private string $table = 'orders'; // Nom de la table pour simplifier les requêtes

    // Constructeur : on passe la connexion PDO depuis le controller
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Crée une commande brouillon
     * @param int $userId ID de l'utilisateur
     * @param float $totalPrice Total initial de la commande
     * @param string $pickupTime Heure de retrait
     * @return int ID de la commande créée
     */
    public function create(int $userId, float $totalPrice, string $pickupTime): int
    {
        $orderNumber = $this->getNextOrderNumber(); // Génère le numéro unique

        $stmt = $this->db->prepare("
            INSERT INTO orders 
            (order_number, order_date, order_total_price, order_pickup_time, order_status, user_id)
            VALUES (?, NOW(), ?, ?, 'brouillon', ?)
        ");
        $stmt->execute([$orderNumber, $totalPrice, $pickupTime, $userId]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Génère le prochain numéro de commande pour la journée
     * @return string Exemple : CMD_1, CMD_2, etc.
     */
    private function getNextOrderNumber(): string
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $resetHour = 19; // Heure à partir de laquelle la journée "commande" change

        // Si avant 19h, on considère la commande comme de la veille
        $cmdDay = clone $now;
        if ((int) $now->format('H') < $resetHour) {
            $cmdDay->modify('-1 day');
        }

        $start = $cmdDay->format('Y-m-d') . ' 19:00:00';
        $end = (clone $cmdDay)->modify('+1 day')->format('Y-m-d') . ' 18:59:59';

        // On récupère la dernière commande de la journée pour incrémenter le numéro
        $stmt = $this->db->prepare("
            SELECT order_number
            FROM orders
            WHERE order_date BETWEEN :start AND :end
            ORDER BY order_id DESC
            LIMIT 1
        ");
        $stmt->execute(['start' => $start, 'end' => $end]);

        $last = $stmt->fetchColumn();

        if ($last) {
            $num = (int) str_replace('CMD_', '', $last);
            return 'CMD_' . ($num + 1);
        }

        return 'CMD_1';
    }

    /**
     * Confirme une commande
     * @param int $orderId
     * @return bool
     */
    public function confirm(int $orderId): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE orders SET order_status = 'confirmée' WHERE order_id = ?");
            return $stmt->execute([$orderId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Récupère une commande par son ID
     */
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

    /**
     * Récupère toutes les commandes d'un utilisateur
     */
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

    /**
     * Met à jour le prix total d'une commande en additionnant les order_items
     */
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

    /**
     * Met à jour le statut d'une commande
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        try {
            $sql = "UPDATE orders SET order_status = :status WHERE order_id = :orderId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprime une commande
     */
    public function delete(int $orderId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE order_id = :order_id");
        return $stmt->execute(['order_id' => $orderId]);
    }

    /**
     * Récupère toutes les commandes
     */
    public function getAllOrders(): array
    {
        try {
            $stmt = $this->db->query("SELECT * FROM orders ORDER BY order_date ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Compte le nombre de commandes sur un créneau donné
     */
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
}