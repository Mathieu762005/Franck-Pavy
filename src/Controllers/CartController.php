<?php
// On déclare le namespace du contrôleur pour organiser le code
namespace App\Controllers;

// On importe les modèles dont on aura besoin
use App\Models\Cart;      // Modèle pour gérer le panier
use App\Models\Product;   // Modèle pour gérer les produits
use PDO;                  // Classe PHP pour la base de données

// Début de la classe CartController
namespace App\Controllers;

use App\Models\Cart;
use App\Models\Product;
use PDO;

class CartController
{
    // Propriétés pour gérer le panier et les produits
    private Cart $cart;
    private Product $productModel;

    // Constructeur : instancie les modèles avec la connexion DB
    public function __construct(PDO $db)
    {
        $this->cart = new Cart($db);
        $this->productModel = new Product($db);
    }

    // ---------- MÉTHODES INTERNES ----------

    // Récupère l'ID de l'utilisateur connecté ou null
    private function getUserId(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    // Vérifie si l'utilisateur est connecté
    // Si non, redirige vers login
    private function checkUser(): int
    {
        $userId = $this->getUserId();
        if (!$userId) {
            header('Location: index.php?url=login');
            exit;
        }
        return $userId;
    }

    // ---------- MÉTHODES PUBLIQUES ----------

    // Retourne tous les articles du panier de l'utilisateur
    public function viewCart(): array
    {
        $userId = $this->checkUser();
        return $this->cart->getAllItems($userId);
    }

    // Vérifie si le stock est suffisant
    private function checkStock(array $product, int $quantity): bool
    {
        if ((int) $product['product_available'] < $quantity) {
            $_SESSION['errors'][] = "Stock insuffisant pour '{$product['product_name']}'.";
            return false;
        }
        return true;
    }

    // Ajouter un produit au panier
    public function addToCart(int $productId, int $quantity = 1): bool
    {
        $userId = $this->checkUser();

        $product = $this->productModel->getById($productId);
        if (!$product)
            return false;

        $existingItem = $this->cart->getItemByProductId($userId, $productId);

        if ($existingItem) {
            $newQty = $existingItem['cart_item_quantity'] + $quantity;
            if (!$this->checkStock($product, $newQty))
                return false;
            return $this->updateItem(
                $existingItem['cart_item_id'],
                $newQty,
                $existingItem['cart_item_unit_price']
            );
        }

        if (!$this->checkStock($product, $quantity))
            return false;

        return $this->cart->addItem(
            $userId,
            $productId,
            $product['product_name'],
            (float) $product['product_price'],
            $quantity
        );
    }

    // Supprimer un article
    public function removeItem(int $cartItemId): bool
    {
        return $this->cart->removeItem($cartItemId);
    }

    // Mettre à jour un article (quantité + prix)
    public function updateItem(int $cartItemId, int $quantity, float $unitPrice): bool
    {
        return $this->cart->updateItem($cartItemId, $quantity, $unitPrice);
    }

    // Diminuer la quantité
    public function decreaseQuantity(int $cartItemId): bool
    {
        $item = $this->cart->getCartItemById($cartItemId);
        if (!$item)
            return false;

        $newQty = $item['cart_item_quantity'] - 1;

        return $newQty > 0
            ? $this->updateItem($cartItemId, $newQty, $item['cart_item_unit_price'])
            : $this->removeItem($cartItemId);
    }

    // ---------- MÉTHODES "HANDLE POST" ----------

    public function handleAddFromPost(): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);
        if ($productId > 0)
            $this->addToCart($productId, $quantity);
    }

    public function handleRemoveFromPost(): void
    {
        $this->removeItem((int) ($_POST['cart_item_id'] ?? 0));
    }

    public function handleUpdateAllFromPost(): void
    {
        $quantities = $_POST['quantities'] ?? [];
        $unitPrices = $_POST['unit_prices'] ?? [];
        foreach ($quantities as $cartItemId => $quantity) {
            $unitPrice = (float) ($unitPrices[$cartItemId] ?? 0);
            $this->updateItem((int) $cartItemId, (int) $quantity, $unitPrice);
        }
    }

    public function handleDecreaseFromPost(): void
    {
        $this->decreaseQuantity((int) ($_POST['cart_item_id'] ?? 0));
    }
}