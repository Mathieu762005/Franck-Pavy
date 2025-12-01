<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\CartItem;

class OrderController
{
    private Order $orderModel;
    private User $userModel;
    private CartItem $cartModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userModel  = new User();
        $this->cartModel  = new CartItem();
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(int $orderId, string $newStatus): bool
    {
        $updated = $this->orderModel->updateStatus($orderId, $newStatus);

        if ($updated && $newStatus === 'terminée') {
            $this->finalizeOrder($orderId);
        }

        return $updated;
    }

    /**
     * Logique finale quand une commande est terminée
     */
    private function finalizeOrder(int $orderId): void
    {
        $order = $this->orderModel->findById($orderId);

        if (!$order) {
            throw new \Exception("Commande introuvable");
        }

        // 1. Mettre à jour les stats du client
        $this->userModel->id = $order['user_id'];
        $this->userModel->incrementStats($order['order_total_price']);

        // 2. Supprimer les lignes du panier liées à cette commande
        $this->cartModel->deleteItemsByOrder($orderId);

        // 3. Supprimer la commande
        $this->orderModel->delete($orderId);
    }
}
