<?php

namespace App\Controllers;

use App\Models\CartItem;

class CartItemController
{
    private CartItem $cartItemModel;

    public function __construct()
    {
        $this->cartItemModel = new CartItem();
    }

    /**
     * Afficher tous les items d'une commande
     */
    public function showItems(int $orderId): array
    {
        return $this->cartItemModel->getItemsByOrder($orderId);
    }

    /**
     * Ajouter un produit au panier
     */
    public function addItem(int $orderId, int $productId, string $productName, int $quantity, float $unitPrice): bool
    {
        return $this->cartItemModel->addItem($orderId, $productId, $productName, $quantity, $unitPrice);
    }

    /**
     * Supprimer tous les items liés à une commande
     */
    public function clearOrderItems(int $orderId): bool
    {
        return $this->cartItemModel->deleteItemsByOrder($orderId);
    }
}
