<?php
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use PDO;

class CartController
{
    private Cart $cart;       // <--- Déclarer la propriété
    private Product $productModel;

    public function __construct(PDO $db)
    {
        $this->cart = new Cart($db);          // <--- Instancier Cart
        $this->productModel = new Product($db);
    }

    public function viewCart(): array
    {
        // Récupérer l'ID de l'utilisateur connecté depuis la session
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            // Si pas connecté, rediriger vers la page de login
            header('Location: index.php?url=login');
            exit;
        }

        // Récupérer tous les items du panier pour cet utilisateur
        return $this->cart->getAllItems($userId);
    }

    /**
     * Récupérer un item du panier par produit
     */
    public function getItemByProductId(int $userId, int $productId): ?array
    {
        return $this->cart->getItemByProductId($userId, $productId);
    }

    /**
     * Ajouter un produit au panier (évite les doublons)
     */
    public function addToCart(int $userId, int $productId, int $quantity = 1): bool
    {
        // Vérifier si le produit est déjà dans le panier
        $existingItem = $this->getItemByProductId($userId, $productId);

        if ($existingItem) {
            // Mettre à jour la quantité
            $newQuantity = $existingItem['cart_items_quantity'] + $quantity;
            $unitPrice = $existingItem['cart_items_unit_price'];
            return $this->updateItem($existingItem['cart_item_id'], $newQuantity, $unitPrice);
        }

        // Sinon, créer un nouvel item
        $product = $this->productModel->getById($productId);
        if (!$product)
            return false;

        $unitPrice = (float) $product['product_price'];
        $totalPrice = $unitPrice * $quantity;

        return $this->cart->addItem($userId, $productId, $product['product_name'], $unitPrice, $quantity);
    }

    /**
     * Supprimer un item du panier
     */
    public function removeItem(int $cartItemId): bool
    {
        return $this->cart->removeItem($cartItemId);
    }

    /**
     * Mettre à jour un item du panier
     */
    public function updateItem(int $cartItemId, int $quantity, float $unitPrice): bool
    {
        return $this->cart->updateItem($cartItemId, $quantity, $unitPrice);
    }

    /**
     * Diminuer la quantité d'un item
     */
    public function decreaseQuantity(int $cartItemId): bool
    {
        // Récupérer l'article
        $item = $this->cart->getCartItemById($cartItemId);

        if (!$item) {
            return false;
        }

        $qty = (int) $item['cart_items_quantity'];

        if ($qty > 1) {
            // Diminuer la quantité
            $unitPrice = (float) $item['cart_items_unit_price'];
            $newQty = $qty - 1;

            return $this->cart->updateItem($cartItemId, $newQty, $unitPrice);

        } else {
            // Si quantité = 1 → supprimer la ligne
            return $this->cart->removeItem($cartItemId);
        }
    }
}