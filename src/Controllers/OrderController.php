<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use PDO;

class OrderController
{
    private Order $order;
    private OrderItem $orderItem;

    public function __construct(PDO $db)
    {
        $this->order = new Order($db);
        $this->orderItem = new OrderItem($db);
    }

    // Checkout : créer une commande et copier le panier
    public function checkout(int $userId, float $totalPrice, string $pickupTime): int
    {
        // Créer une commande brouillon
        $orderId = $this->order->create($userId, $totalPrice, $pickupTime);

        if ($orderId > 0) {
            // Copier le panier dans order_items
            $this->orderItem->copyCartToOrder($orderId, $userId);
        }

        return $orderId;
    }

    // Récupérer les détails d'une commande
    public function getOrderDetails(int $orderId): array
    {
        $orderData = $this->order->getById($orderId);
        $items = $this->orderItem->getByOrder($orderId);

        return [
            'order' => $orderData,
            'items' => $items
        ];
    }

    // Confirmer la commande
    public function confirmOrder(int $orderId): bool
    {
        return $this->order->confirm($orderId);
    }

    // Récupérer toutes les commandes d'un utilisateur
    public function getUserOrders(int $userId): array
    {
        return $this->order->getByUser($userId);
    }
}