<?php

namespace App\Models;

use App\Models\DataBase;
use PDO;
use PDOException;

class AdminCommande
{
    private PDO $db; // Connexion à la base de données

    public function __construct()
    {
        // On utilise la classe Database pour créer une instance PDO
        $this->db = DataBase::createInstancePDO();
    }



    public function findAll(): array
    {
        $sql = "
        SELECT 
            o.order_id,
            o.order_number,
            o.order_date,
            o.order_total_price,
            o.order_pickup_time,
            o.order_status,
            u.user_name,
            u.user_email
        FROM orders o
        JOIN users u ON u.user_id = o.user_id
        ORDER BY o.order_date DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findDetailsByOrder(int $orderId): array
    {
        $sql = "
        SELECT 
            p.product_name,
            oi.quantity,
            oi.unit_price,
            (oi.quantity * oi.unit_price) AS total_line
        FROM order_items oi
        JOIN products p ON p.product_id = oi.product_id
        WHERE oi.order_id = ?
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function detailModel(): array
    {
        try {
            // Requête SQL pour récupérer toutes les commandes triées par date décroissante
            $stmt = $this->db->query("SELECT * FROM orders_items ORDER BY order_items_id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourne un tableau associatif
        } catch (PDOException $e) {
            // En cas d'erreur SQL, retourne un tableau vide
            return [];
        }
    }

    public function updateStatus(int $orderId, string $status)
    {
        $stmt = $this->db->prepare("UPDATE orders SET order_status = :status WHERE order_id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $orderId
        ]);
    }

    public function findAllWithDetails(): array
    {
        $sql = "
        SELECT 
            o.order_id,
            o.order_number,
            o.order_date,
            o.order_total_price,
            o.order_pickup_time,
            o.order_status,
            u.user_name,
            u.user_email
        FROM orders o
        JOIN users u ON u.user_id = o.user_id
        ORDER BY o.order_date DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$orders) {
            return [];
        }

        $detailStmt = $this->db->prepare("
        SELECT 
            p.product_name,
            oi.quantity,
            oi.unit_price,
            (oi.quantity * oi.unit_price) AS total_line
        FROM order_items oi
        JOIN products p ON p.product_id = oi.product_id
        WHERE oi.order_id = ?
    ");

        // Ajoute les détails pour chaque commande
        foreach ($orders as &$order) {
            $detailStmt->execute([$order['order_id']]);
            $order['details'] = $detailStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        unset($order);

        return $orders;
    }

    /**
     * Insère un nouveau produit en base
     */
    public function insertProduit(
        string $name,
        string $subtitle,
        string $description,
        float $price,
        string $image,
        int $categoryId,
        int $stock
    ): bool {
        $sql = "
        INSERT INTO products
            (product_name, product_subtitle, product_description, product_price, product_image, category_id, product_available)
        VALUES
            (:name, :subtitle, :description, :price, :image, :category, :stock)
    ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'name' => $name,
            'subtitle' => $subtitle,
            'description' => $description,
            'price' => $price,
            'image' => $image,
            'category' => $categoryId,
            'stock' => $stock
        ]);
    }
}
