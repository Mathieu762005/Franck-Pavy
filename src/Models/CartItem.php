<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class CartItem
{
    public int $id;
    public int $order_id;
    public int $product_id;
    public string $product_name;
    public int $quantity;
    public float $unit_price;
    public float $total_price;

    /**
     * Récupérer tous les items d'une commande
     */
    public function getItemsByOrder(int $orderId)
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "SELECT * FROM `cart_items` WHERE `order_id` = :orderId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Ajouter un item dans le panier
     */
    public function addItem(int $orderId, int $productId, string $productName, int $quantity, float $unitPrice): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "INSERT INTO `cart_items` 
                    (`order_id`, `product_id`, `product_name`, `cart_items_quantity`, `cart_items_unit_price`, `cart_items_total_price`)
                    VALUES (:orderId, :productId, :productName, :quantity, :unitPrice, :totalPrice)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->bindValue(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindValue(':productName', $productName, PDO::PARAM_STR);
            $stmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindValue(':unitPrice', $unitPrice, PDO::PARAM_STR);
            $stmt->bindValue(':totalPrice', $unitPrice * $quantity, PDO::PARAM_STR);

            return $stmt->execute();

        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Supprimer tous les items liés à une commande
     */
    public function deleteItemsByOrder(int $orderId): bool
    {
        try {
            $pdo = Database::createInstancePDO();

            if (!$pdo) {
                return false;
            }

            $sql = "DELETE FROM `cart_items` WHERE `order_id` = :orderId";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':orderId', $orderId, PDO::PARAM_INT);

            return $stmt->execute();
            
        } catch (PDOException $e) {
            return false;
        }
    }
}
