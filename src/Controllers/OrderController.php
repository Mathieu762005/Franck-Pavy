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

    // Créer une commande depuis le panier
    public function checkout(int $userId, string $pickupTime): ?int
    {
        $cartItems = $this->cart->getAllItems($userId);
        if (empty($cartItems))
            return null;

        $totalPrice = array_sum(array_column($cartItems, 'cart_items_total_price'));

        $orderId = $this->order->create($userId, $totalPrice, $pickupTime);
        if ($orderId > 0) {
            $this->orderItem->copyCartToOrder($orderId, $userId);
            $this->cart->clearUserCart($userId);
        }

        return $orderId;
    }

    // Récupérer les détails d'une commande
    public function getOrderDetails(int $orderId): array
    {
        $orderData = $this->order->getById($orderId);
        $items = $this->orderItem->getByOrder($orderId);

        return ['order' => $orderData, 'items' => $items];
    }

    // Récupérer toutes les commandes d'un utilisateur
    public function getUserOrders(int $userId): array
    {
        return $this->order->getByUser($userId);
    }

    // Affiche le formulaire Click & Collect
    public function getTimeSlots(): array
    {
        $now = strtotime(date('H:i'));
        $timeslots = [];
        $start = strtotime('08:00');
        $end = strtotime('20:00');

        while ($start <= $end) {
            $slot = date('H:i', $start);
            $timeslots[] = [
                'time' => $slot,
                'full' => $this->order->getReservationCount($slot) >= 10,
                'past' => $start <= $now
            ];
            $start = strtotime('+30 minutes', $start);
        }

        return $timeslots;
    }

    // Traitement du formulaire Click & Collect
    public function submitPickupTime()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pickup_time'])) {
            $pickupTime = $_POST['pickup_time'];
            $userId = $_SESSION['user']['id'] ?? null;

            if (!$userId) {
                $_SESSION['error'] = "Vous devez être connecté pour passer une commande.";
                header("Location: index.php?url=login");
                exit;
            }

            if ($this->order->getReservationCount($pickupTime) >= 10) {
                $_SESSION['error'] = "Ce créneau est complet. Veuillez choisir un autre horaire.";
                header("Location: index.php?url=click_and_collect");
                exit;
            }

            $cartItems = $this->cart->getAllItems($userId);
            if (empty($cartItems)) {
                $_SESSION['error'] = "Votre panier est vide.";
                header("Location: index.php?url=04_click_and_collect");
                exit;
            }

            $totalPrice = array_sum(array_column($cartItems, 'cart_items_total_price'));

            $orderId = $this->order->create($userId, $totalPrice, $pickupTime);
            if ($orderId > 0) {
                $this->orderItem->copyCartToOrder($orderId, $userId);
                $this->cart->clearUserCart($userId);
                $_SESSION['success'] = "Commande enregistrée avec succès pour $pickupTime.";
                header("Location: index.php?url=order_confirmation&order_id=$orderId");
                exit;
            }

            $_SESSION['error'] = "Une erreur est survenue lors de la création de votre commande.";
            header("Location: index.php?url=click_and_collect");
            exit;
        }
    }
}