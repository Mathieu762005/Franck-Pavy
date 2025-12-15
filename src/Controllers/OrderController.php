<?php
namespace App\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use DateTime;
use DateTimeZone;
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

    public function showForm(): array
    {
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $day = $now->format('Y-m-d');

        // Générer les créneaux du jour
        $timeslots = $this->generateTimeSlots($day, $now);

        // Si tous les créneaux du jour sont passés, générer ceux du lendemain
        $allPast = array_reduce($timeslots, fn($carry, $slot) => $carry && $slot['past'], true);
        if ($allPast) {
            $tomorrow = (clone $now)->modify('+1 day')->format('Y-m-d');
            $timeslots = $this->generateTimeSlots($tomorrow, $now, true); // ignore les créneaux passés
        }

        return $timeslots;
    }

    /**
     * Génère les créneaux pour une date donnée
     */
    private function generateTimeSlots(string $day, DateTime $now, bool $ignorePast = false): array
    {
        $timeslots = [];
        $start = strtotime('07:00'); // début à 7h
        $end = strtotime('19:00');   // fin à 19h

        while ($start <= $end) {
            $slot = date('H:i', $start);
            $slotTime = new DateTime($day . ' ' . $slot, new DateTimeZone('Europe/Paris'));

            // On ajoute une marge de 15 minutes avant l’heure du créneau
            $slotTime->modify('-15 minutes');

            $count = $this->order->getReservationCount($slot);

            // On ne garde que les créneaux valides
            if ($ignorePast || $slotTime > $now) {
                $timeslots[] = [
                    'time' => $slot,
                    'full' => $count >= 10,
                    'past' => false
                ];
            }

            $start = strtotime('+30 minutes', $start);
        }

        return $timeslots;
    }





    public function getOrderDetailsForDisplay(int $orderId): array
    {
        $orderData = $this->getOrderDetails($orderId); // retourne ['order' => ..., 'items' => ...]

        if (!$orderData || empty($orderData['order'])) {
            return $orderData; // ou gérer erreur ici
        }

        $pickupTime = $orderData['order']['order_pickup_time'] ?? '';
        $displayTime = substr($pickupTime, 0, 5); // HH:MM

        // Déterminer si c'est pour demain
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $orderDate = new DateTime($orderData['order']['order_date'], new DateTimeZone('Europe/Paris'));
        $pickupDate = clone $orderDate;
        $pickupDate->setTime(
            (int) substr($pickupTime, 0, 2),
            (int) substr($pickupTime, 3, 2)
        );

        $forTomorrow = '';
        if ($pickupDate <= $now) {
            $forTomorrow = ' (pour demain)';
        }

        $orderData['display_pickup_time'] = $displayTime . $forTomorrow;

        return $orderData;
    }

    public function getDisplayPickupTime(array $order): string
    {
        $pickupTime = $order['order_pickup_time'] ?? '';
        $displayTime = substr($pickupTime, 0, 5); // HH:MM

        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $orderDate = new DateTime($order['order_date'], new DateTimeZone('Europe/Paris'));
        $pickupDate = clone $orderDate;
        $pickupDate->setTime(
            (int) substr($pickupTime, 0, 2),
            (int) substr($pickupTime, 3, 2)
        );

        $forTomorrow = '';
        if ($pickupDate <= $now) {
            $forTomorrow = ' (pour demain)';
        }

        return $displayTime . $forTomorrow;
    }






    // Traitement du formulaire Click & Collect
    public function submitPickupTime()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['pickup_time'])) {
            return;
        }

        $pickupTime = $_POST['pickup_time'];
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "Vous devez être connecté pour passer une commande.";
            header("Location: index.php?url=login");
            exit;
        }

        // Vérification créneau passé avec marge 15 minutes
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $pickup = DateTime::createFromFormat('H:i', $pickupTime, new DateTimeZone('Europe/Paris'));
        $pickup->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));
        $pickupMinus15 = (clone $pickup)->modify('-15 minutes');

        if ($pickupMinus15 <= $now) {
            $_SESSION['error'] = "Ce créneau est déjà passé ou trop proche.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Vérification créneau complet
        if ($this->order->getReservationCount($pickupTime) >= 10) {
            $_SESSION['error'] = "Ce créneau est complet. Veuillez choisir un autre horaire.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Vérification panier non vide
        $cartItems = $this->cart->getAllItems($userId);
        if (empty($cartItems)) {
            $_SESSION['error'] = "Votre panier est vide.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Création de la commande
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
        header("Location: index.php?url=04_click_and_collect");
        exit;
    }








    public function formatPickupTimeForDisplay(array $order): string
    {
        $pickupTime = $order['order_pickup_time'] ?? '';
        $displayTime = substr($pickupTime, 0, 5); // HH:MM

        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $orderDate = new DateTime($order['order_date'], new DateTimeZone('Europe/Paris'));

        $pickupDate = clone $orderDate;
        $pickupDate->setTime(
            (int) substr($pickupTime, 0, 2),
            (int) substr($pickupTime, 3, 2)
        );

        // Si l’heure de retrait est avant maintenant, considérer que c’est pour demain
        $forTomorrow = '';
        if ($pickupDate <= $now) {
            $forTomorrow = ' (pour demain)';
        }

        return $displayTime . $forTomorrow;
    }








    // ======================
// 🔵 checkoutStripe
// ======================
    public function checkoutStripe()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['pickup_time'])) {
            $_SESSION['error'] = "Aucun créneau choisi.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        $pickupTime = $_POST['pickup_time'];
        $userId = $_SESSION['user']['id'] ?? null;

        if (!$userId) {
            $_SESSION['error'] = "Vous devez être connecté pour passer une commande.";
            header("Location: index.php?url=login");
            exit;
        }

        // Vérification créneau passé
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));
        $pickup = DateTime::createFromFormat('H:i', $pickupTime, new DateTimeZone('Europe/Paris'));
        $pickup->setDate((int) $now->format('Y'), (int) $now->format('m'), (int) $now->format('d'));

        if ($pickup <= $now) {
            $_SESSION['error'] = "Ce créneau est déjà passé.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Vérification créneau complet
        if ($this->order->getReservationCount($pickupTime) >= 10) {
            $_SESSION['error'] = "Ce créneau est complet. Veuillez choisir un autre horaire.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Vérification panier non vide
        $cartItems = $this->cart->getAllItems($userId);
        if (empty($cartItems)) {
            $_SESSION['error'] = "Votre panier est vide.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // 🔹 1. Créer la commande
        $totalPrice = array_sum(array_column($cartItems, 'cart_items_total_price'));
        $orderId = $this->order->create($userId, $totalPrice, $pickupTime);

        if ($orderId <= 0) {
            $_SESSION['error'] = "Impossible de créer la commande.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        $this->orderItem->copyCartToOrder($orderId, $userId);
        $this->cart->clearUserCart($userId);

        // 🔹 2. Créer la session Stripe
        $totalPriceCents = $totalPrice * 100; // en centimes pour Stripe
        \Stripe\Stripe::setApiKey('sk_test_51SeEPH0So1rm7kaSioj0O3pSfICCZ8iFCm5dBsBmdJBPK5FVls4nlHmJKluhL8Gdgz5nen0V99ymuNpatA2vucxO000toFtKP0'); // ta clé secrète

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Commande Click & Collect'],
                        'unit_amount' => $totalPriceCents,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'client_reference_id' => $orderId, // 🔹 important pour relier la commande
            'success_url' => "http://localhost:8000/index.php?url=checkout_success&order_id=$orderId",
            'cancel_url' => 'http://localhost:8000/index.php?url=04_click_and_collect',
        ]);

        // 🔹 3. Redirection vers Stripe
        header("Location: " . $session->url);
        exit;
    }


    public function stripeWebhook()
    {
        // Lire la requête brute de Stripe
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        // Clé secrète de ton webhook Stripe (depuis Stripe CLI ou Dashboard)
        $endpointSecret = 'whsec_fa3af3f0d5f258134f8ad2c19bf11d047be38f77eb0860fdaa3a9c0c52d4d47d';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Payload invalide
            http_response_code(400);
            exit();
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Signature invalide
            http_response_code(400);
            exit();
        }

        // Traiter l'événement
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Récupérer l'ID de commande depuis Stripe (client_reference_id)
            $orderId = $session->client_reference_id ?? null;

            if ($orderId) {
                $this->order->markAsPaid((int) $orderId);
            }
        }

        http_response_code(200);
    }


}
