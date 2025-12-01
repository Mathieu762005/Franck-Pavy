<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class Order
{
    public int $id;
    public string $number;
    public string $date;
    public float $total_price;
    public string $pickup_time;
    public string $status;
    public int $user_id;

    /**
     * Récupérer une commande par son ID
     */
    public function findById(int $orderId)
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "SELECT * FROM `orders` WHERE `order_id` = :orderId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(int $orderId, string $status): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "UPDATE `orders` SET `order_status` = :status WHERE `order_id` = :orderId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprimer une commande
     */
    public function delete(int $orderId): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "DELETE FROM `orders` WHERE `order_id` = :orderId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);

            return $stmt->execute();
            
        } catch (PDOException $e) {
            return false;
        }
    }
}
