<?php
namespace App\Controllers;

use App\Controllers\OrderController;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use PDO;

class StripeController
{
    private OrderController $orderController;

    public function __construct(PDO $db)
    {
        // On utilise directement le contrôleur OrderController pour créer les commandes
        $this->orderController = new OrderController($db);
    }

    /**
     * Redirige avec un message d'erreur
     */
    private function redirectWithError(string $msg, string $url)
    {
        $_SESSION['error'] = $msg;
        header("Location: $url");
        exit;
    }

    /**
     * Crée une session Stripe pour payer la commande
     */
    public function checkoutStripe()
    {
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId)
            $this->redirectWithError("Vous devez être connecté.", "index.php?url=login");

        $pickupTime = $_POST['pickup_time'] ?? null;
        if (!$pickupTime)
            $this->redirectWithError("Aucun créneau choisi.", "index.php?url=04_click_and_collect");

        // Crée la commande avec la méthode existante du contrôleur
        $orderId = $this->orderController->createOrder($userId, $pickupTime);
        if (!$orderId)
            $this->redirectWithError("Impossible de créer la commande.", "index.php?url=04_click_and_collect");

        // Création de la session Stripe
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $orderDetails = $this->orderController->getOrderDetails($orderId);
        $totalPrice = $orderDetails['order']['order_total_price'] ?? 0;
        $totalCents = $totalPrice * 100;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => ['name' => 'Commande Click & Collect'],
                        'unit_amount' => $totalCents,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'client_reference_id' => $orderId,
            'success_url' => "http://localhost:8000/index.php?url=checkout_success&order_id=$orderId",
            'cancel_url' => "http://localhost:8000/index.php?url=04_click_and_collect",
        ]);

        header("Location: " . $session->url);
        exit;
    }

    /**
     * Webhook Stripe pour valider le paiement
     */
    public function stripeWebhook()
    {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';
        $endpointSecret = $_ENV['STRIPE_WEBHOOK_SECRET'] ?? null;

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            http_response_code(400);
            exit();
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->client_reference_id ?? null;
            if ($orderId)
                $this->orderController->getOrderModel()->markAsPaid((int) $orderId);
        }

        http_response_code(200);
    }
}