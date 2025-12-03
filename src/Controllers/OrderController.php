<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use PDO;

class OrderController
{
    private Order $order;
    private OrderItem $orderItem;
    private Cart $cart;

    public function __construct(PDO $db)
    {
        $this->order = new Order($db);
        $this->orderItem = new OrderItem($db);
        $this->cart = new Cart($db);
    }

    // Checkout : créer une commande et copier le panier
    public function checkout(int $userId, string $pickupTime): ?int
    {
        // 1️⃣ Récupérer les items du panier
        $cartItems = $this->cart->getAllItems($userId);
        if (empty($cartItems)) {
            return null; // panier vide
        }

        // 2️⃣ Calculer le total du panier
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['cart_items_total_price'];
        }

        // 3️⃣ Créer une commande brouillon
        $orderId = $this->order->create($userId, $totalPrice, $pickupTime);

        if ($orderId > 0) {
            // 4️⃣ Copier les items du panier vers order_items
            $this->orderItem->copyCartToOrder($orderId, $userId);

            // 5️⃣ Vider le panier
            $this->cart->clearUserCart($userId);
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