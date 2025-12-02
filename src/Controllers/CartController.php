<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use PDO;

class CartController
{
    private Cart $cart;
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->cart = new Cart($db);
        $this->productModel = new Product($db);
    }

    public function viewCart(int $userId): array
    {
        return $this->cart->getUserCart($userId);
    }

    // Ajouter un produit au panier
    public function addToCart(int $userId, int $productId, int $quantity = 1)
    {
        $product = $this->productModel->getById($productId);

        if (!$product)
            return;

        // Vérifier si le produit est disponible
        if ($product['product_available'] == 0) {
            die("Produit non disponible !");
        }

        $productName = $product['product_name'];
        $unitPrice = (float) $product['product_price'];

        $this->cart->addItem($userId, $productId, $productName, $unitPrice, $quantity);
    }

    public function removeItem(int $cartItemId): void
    {
        $this->cart->removeItem($cartItemId);
    }

    public function updateItem(int $cartItemId, int $quantity, float $unitPrice): bool
    {
        return $this->cart->updateItem($cartItemId, $quantity, $unitPrice);
    }
}